<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CreatureRequest;
use App\Models\Fish;
use App\Models\Region;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreatureRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user && ($user->is_admin || $user->hasRole('super_admin'))) {
            return redirect('/admin');
        }

        $requests = CreatureRequest::with(['fish', 'region', 'category'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('requests.index', compact('requests'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user && ($user->is_admin || $user->hasRole('super_admin'))) {
            return redirect('/admin');
        }

        $regions = Region::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        $fishes = Fish::with(['region', 'category'])
            ->published()
            ->orderBy('name')
            ->get();

        return view('requests.create', compact('regions', 'categories', 'fishes'));
    }

    public function fishData(Fish $fish): JsonResponse
    {
        return response()->json([
            'id' => $fish->id,
            'region_id' => $fish->region_id,
            'category_id' => $fish->category_id,
            'name' => $fish->name,
            'scientific_name' => $fish->scientific_name,
            'habitat' => $fish->habitat,
            'food' => $fish->food,
            'characteristics' => $fish->characteristics,
            'description' => $fish->description,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user && ($user->is_admin || $user->hasRole('super_admin'))) {
            return redirect('/admin');
        }

        $data = $request->validate([
            'request_type' => ['required', 'in:add,update'],
            'fish_id' => ['nullable', 'required_if:request_type,update', 'exists:fishes,id'],
            'region_id' => ['required', 'exists:regions,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'scientific_name' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
            'habitat' => ['nullable', 'string', 'max:255'],
            'food' => ['nullable', 'string', 'max:255'],
            'characteristics' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'request_note' => ['nullable', 'string'],
        ], [
            'fish_id.required_if' => 'Pilih ikan yang ingin diubah jika jenis request adalah perubahan data.',
        ]);

        if ($data['request_type'] === 'add') {
            $data['fish_id'] = null;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('request-fishes', 'public');
        }

        $data['user_id'] = Auth::id();
        $data['request_status'] = 'pending';

        CreatureRequest::create($data);

        return redirect()
            ->route('creature-requests.index')
            ->with('success', 'Request berhasil dikirim dan menunggu persetujuan admin.');
    }
}
