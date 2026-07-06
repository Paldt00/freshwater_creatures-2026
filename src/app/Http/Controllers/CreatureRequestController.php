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
        $user = $this->getAuthenticatedUser();

        if ($this->isAdmin($user)) {
            return redirect('/admin');
        }

        $requests = CreatureRequest::query()
            ->with([
                'fish',
                'region',
                'category',
                'reviewer',
            ])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view(
            'requests.index',
            compact('requests')
        );
    }

    public function create(): View|RedirectResponse
    {
        $user = $this->getAuthenticatedUser();

        if ($this->isAdmin($user)) {
            return redirect('/admin');
        }

        $regions = Region::query()
            ->orderBy('name')
            ->get();

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        $fishes = Fish::query()
            ->with([
                'region',
                'category',
            ])
            ->published()
            ->orderBy('name')
            ->get();

        $conservationStatuses =
            Fish::CONSERVATION_STATUSES;

        $biogeographyTypes =
            Fish::BIOGEOGRAPHY_TYPES;

        return view(
            'requests.create',
            compact(
                'regions',
                'categories',
                'fishes',
                'conservationStatuses',
                'biogeographyTypes'
            )
        );
    }

    public function fishData(
        Fish $fish
    ): JsonResponse {
        $user = $this->getAuthenticatedUser();

        if ($this->isAdmin($user)) {
            return response()->json([
                'message' =>
                    'Administrator tidak dapat membuat pengajuan.',
            ], 403);
        }

        if (! $fish->is_published) {
            return response()->json([
                'message' =>
                    'Data ikan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'id' => $fish->id,
            'region_id' => $fish->region_id,
            'category_id' => $fish->category_id,
            'name' => $fish->name,
            'scientific_name' =>
                $fish->scientific_name,
            'habitat' => $fish->habitat,
            'food' => $fish->food,
            'conservation_status' =>
                $fish->conservation_status,
            'biogeography' =>
                $fish->biogeography,
            'characteristics' =>
                $fish->characteristics,
            'description' =>
                $fish->description,
        ]);
    }

    public function store(
        Request $request
    ): RedirectResponse {
        $user = $this->getAuthenticatedUser();

        if ($this->isAdmin($user)) {
            return redirect('/admin');
        }

        $validated = $request->validate(
            [
                'request_type' => [
                    'required',
                    Rule::in([
                        'add',
                        'update',
                    ]),
                ],

                'fish_id' => [
                    'nullable',
                    'required_if:request_type,update',
                    Rule::exists(
                        'fishes',
                        'id'
                    )->where(
                        fn ($query) =>
                            $query->where(
                                'is_published',
                                true
                            )
                    ),
                ],

                'region_id' => [
                    'required',
                    Rule::exists(
                        'regions',
                        'id'
                    ),
                ],

                'category_id' => [
                    'required',
                    Rule::exists(
                        'categories',
                        'id'
                    ),
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
                    'file',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:2048',
                ],

                'habitat' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'food' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'conservation_status' => [
                    'required',
                    Rule::in(
                        array_keys(
                            Fish::CONSERVATION_STATUSES
                        )
                    ),
                ],

                'biogeography' => [
                    'required',
                    Rule::in(
                        array_keys(
                            Fish::BIOGEOGRAPHY_TYPES
                        )
                    ),
                ],

                'characteristics' => [
                    'required',
                    'string',
                    'max:5000',
                ],

                'description' => [
                    'required',
                    'string',
                    'max:10000',
                ],

                'request_note' => [
                    'nullable',
                    'string',
                    'max:3000',
                ],
            ],
            [
                'request_type.required' =>
                    'Jenis pengajuan wajib dipilih.',

                'request_type.in' =>
                    'Jenis pengajuan tidak valid.',

                'fish_id.required_if' =>
                    'Pilih ikan yang ingin diubah.',

                'fish_id.exists' =>
                    'Data ikan yang dipilih tidak ditemukan.',

                'region_id.required' =>
                    'Wilayah wajib dipilih.',

                'region_id.exists' =>
                    'Wilayah yang dipilih tidak valid.',

                'category_id.required' =>
                    'Kategori wajib dipilih.',

                'category_id.exists' =>
                    'Kategori yang dipilih tidak valid.',

                'name.required' =>
                    'Nama ikan wajib diisi.',

                'name.max' =>
                    'Nama ikan maksimal 255 karakter.',

                'scientific_name.max' =>
                    'Nama ilmiah maksimal 255 karakter.',

                'image.file' =>
                    'Gambar yang diunggah tidak valid.',

                'image.image' =>
                    'File yang diunggah harus berupa gambar.',

                'image.mimes' =>
                    'Format gambar harus JPG, JPEG, PNG, atau WebP.',

                'image.max' =>
                    'Ukuran gambar maksimal 2 MB.',

                'habitat.required' =>
                    'Habitat wajib diisi.',

                'habitat.max' =>
                    'Habitat maksimal 255 karakter.',

                'food.required' =>
                    'Informasi makanan wajib diisi.',

                'food.max' =>
                    'Informasi makanan maksimal 255 karakter.',

                'conservation_status.required' =>
                    'Status kelestarian wajib dipilih.',

                'conservation_status.in' =>
                    'Status kelestarian tidak valid.',

                'biogeography.required' =>
                    'Biogeografi wajib dipilih.',

                'biogeography.in' =>
                    'Biogeografi tidak valid.',

                'characteristics.required' =>
                    'Ciri-ciri ikan wajib diisi.',

                'characteristics.max' =>
                    'Ciri-ciri maksimal 5.000 karakter.',

                'description.required' =>
                    'Deskripsi ikan wajib diisi.',

                'description.max' =>
                    'Deskripsi maksimal 10.000 karakter.',

                'request_note.max' =>
                    'Catatan maksimal 3.000 karakter.',
            ]
        );

        if (
            $validated['request_type']
            === 'add'
        ) {
            $validated['fish_id'] = null;
        }

        if (
            $validated['request_type']
            === 'update'
        ) {
            $hasPendingRequest =
                CreatureRequest::query()
                    ->where(
                        'user_id',
                        $user->id
                    )
                    ->where(
                        'fish_id',
                        $validated['fish_id']
                    )
                    ->where(
                        'request_type',
                        'update'
                    )
                    ->where(
                        'request_status',
                        'pending'
                    )
                    ->exists();

            if ($hasPendingRequest) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Ikan tersebut masih memiliki pengajuan perubahan yang menunggu pemeriksaan.'
                    );
            }
        }

        if ($request->hasFile('image')) {
            $validated['image'] =
                $request
                    ->file('image')
                    ->store(
                        'request-fishes',
                        'public'
                    );
        }

        $validated['user_id'] =
            $user->id;

        $validated['request_status'] =
            'pending';

        CreatureRequest::query()
            ->create($validated);

        return redirect()
            ->route(
                'creature-requests.index'
            )
            ->with(
                'success',
                'Pengajuan berhasil dikirim dan menunggu pemeriksaan administrator.'
            );
    }

    private function getAuthenticatedUser(): User
    {
        $user = Auth::user();

        abort_unless(
            $user instanceof User,
            403,
            'Anda harus masuk terlebih dahulu.'
        );

        return $user;
    }

    private function isAdmin(
        User $user
    ): bool {
        return $user->is_admin
            || $user->hasRole(
                'super_admin'
            );
    }
}