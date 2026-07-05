<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CreatureRequest;
use App\Models\Fish;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CreatureRequestController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        if (
            $user instanceof User
            && ($user->is_admin || $user->hasRole('super_admin'))
        ) {
            return redirect('/admin');
        }

        $requests = CreatureRequest::with([
            'fish',
            'region',
            'category',
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('requests.index', compact('requests'));
    }

    public function create(): View|RedirectResponse
    {
        $user = Auth::user();

        if (
            $user instanceof User
            && ($user->is_admin || $user->hasRole('super_admin'))
        ) {
            return redirect('/admin');
        }

        $regions = Region::query()
            ->orderBy('name')
            ->get();

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $fishes = Fish::query()
            ->with(['region', 'category'])
            ->published()
            ->orderBy('name')
            ->get();

        $conservationStatuses = Fish::CONSERVATION_STATUSES;
        $biogeographyTypes = Fish::BIOGEOGRAPHY_TYPES;

        return view('requests.create', compact(
            'regions',
            'categories',
            'fishes',
            'conservationStatuses',
            'biogeographyTypes'
        ));
    }

    public function fishData(Fish $fish): JsonResponse
    {
        $user = Auth::user();

        if (
            $user instanceof User
            && ($user->is_admin || $user->hasRole('super_admin'))
        ) {
            return response()->json([
                'message' => 'Administrator tidak perlu membuat request.',
            ], 403);
        }

        return response()->json([
            'id' => $fish->id,
            'region_id' => $fish->region_id,
            'category_id' => $fish->category_id,
            'name' => $fish->name,
            'scientific_name' => $fish->scientific_name,
            'habitat' => $fish->habitat,
            'food' => $fish->food,
            'conservation_status' => $fish->conservation_status,
            'biogeography' => $fish->biogeography,
            'characteristics' => $fish->characteristics,
            'description' => $fish->description,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (
            $user instanceof User
            && ($user->is_admin || $user->hasRole('super_admin'))
        ) {
            return redirect('/admin');
        }

        $data = $request->validate([
            'request_type' => [
                'required',
                Rule::in(['add', 'update']),
            ],

            'fish_id' => [
                'nullable',
                'required_if:request_type,update',
                'exists:fishes,id',
            ],

            'region_id' => [
                'required',
                'exists:regions,id',
            ],

            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'scientific_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'image' => [
                'nullable',
                'image',
                'max:2048',
            ],

            'habitat' => [
                'nullable',
                'string',
                'max:255',
            ],

            'food' => [
                'nullable',
                'string',
                'max:255',
            ],

            'conservation_status' => [
                'required',
                Rule::in(array_keys(Fish::CONSERVATION_STATUSES)),
            ],

            'biogeography' => [
                'required',
                Rule::in(array_keys(Fish::BIOGEOGRAPHY_TYPES)),
            ],

            'characteristics' => [
                'nullable',
                'string',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'request_note' => [
                'nullable',
                'string',
            ],
        ], [
            'fish_id.required_if' => 'Pilih ikan yang ingin diubah.',
            'conservation_status.required' => 'Status kelestarian wajib dipilih.',
            'conservation_status.in' => 'Status kelestarian tidak valid.',
            'biogeography.required' => 'Biogeografi wajib dipilih.',
            'biogeography.in' => 'Biogeografi tidak valid.',
        ]);

        if ($data['request_type'] === 'add') {
            $data['fish_id'] = null;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request
                ->file('image')
                ->store('request-fishes', 'public');
        }

        $data['user_id'] = Auth::id();
        $data['request_status'] = 'pending';

        CreatureRequest::create($data);

        return redirect()
            ->route('creature-requests.index')
            ->with(
                'success',
                'Request berhasil dikirim dan menunggu persetujuan admin.'
            );
    }
}
