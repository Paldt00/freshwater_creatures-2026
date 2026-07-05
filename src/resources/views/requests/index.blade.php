@extends('layouts.public')

@section('title', 'Pengajuan Saya')

@section('content')
<div class="container">
    <div class="section-head">
        <div>
            <h2>Pengajuan Saya</h2>

            <p>
                Riwayat pengajuan penambahan dan perubahan data ikan air tawar.
            </p>
        </div>

        <a
            href="{{ route('creature-requests.create') }}"
            class="btn"
        >
            Ajukan Data
        </a>
    </div>

    <div class="table-card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Ikan</th>
                        <th>Jenis Pengajuan</th>
                        <th>Wilayah</th>
                        <th>Kategori</th>
                        <th>Status Kelestarian</th>
                        <th>Status Pengajuan</th>
                        <th>Catatan Administrator</th>
                        <th>Tanggal Pengajuan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($requests as $creatureRequest)
                        @php
                            $conservationStyle = match(
                                $creatureRequest->conservation_status
                            ) {
                                'extinct' =>
                                    'background:#f3f4f6;color:#374151;',

                                'endangered' =>
                                    'background:#fef2f2;color:#dc2626;',

                                'least_concern' =>
                                    'background:#ecfdf5;color:#16a34a;',

                                default =>
                                    'background:#fff7ed;color:#d97706;',
                            };

                            $requestStatusStyle = match(
                                $creatureRequest->request_status
                            ) {
                                'approved' =>
                                    'background:#ecfdf5;color:#15803d;',

                                'rejected' =>
                                    'background:#fef2f2;color:#dc2626;',

                                default =>
                                    'background:#fff7ed;color:#d97706;',
                            };
                        @endphp

                        <tr>
                            <td>
                                <strong>
                                    {{ $creatureRequest->name }}
                                </strong>

                                @if($creatureRequest->scientific_name)
                                    <br>

                                    <small>
                                        <em>
                                            {{ $creatureRequest->scientific_name }}
                                        </em>
                                    </small>
                                @endif
                            </td>

                            <td>
                                <span class="badge">
                                    {{ $creatureRequest->request_type_label }}
                                </span>
                            </td>

                            <td>
                                {{ $creatureRequest->region?->name ?? '-' }}
                            </td>

                            <td>
                                {{ $creatureRequest->category?->name ?? '-' }}
                            </td>

                            <td>
                                <span
                                    class="badge"
                                    style="{{ $conservationStyle }}"
                                >
                                    {{
                                        $creatureRequest
                                            ->conservation_status_label
                                    }}
                                </span>
                            </td>

                            <td>
                                <span
                                    class="badge"
                                    style="{{ $requestStatusStyle }}"
                                >
                                    {{ $creatureRequest->request_status_label }}
                                </span>
                            </td>

                            <td>
                                {{
                                    $creatureRequest->admin_note
                                    ?: 'Belum ada catatan'
                                }}
                            </td>

                            <td>
                                {{
                                    $creatureRequest->created_at
                                        ?->format('d M Y, H:i')
                                    ?? '-'
                                }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty">
                                    <div
                                        style="
                                            font-size: 42px;
                                            margin-bottom: 10px;
                                        "
                                    >
                                        📄
                                    </div>

                                    <strong>
                                        Belum ada pengajuan yang dikirim.
                                    </strong>

                                    <p style="margin-top: 8px;">
                                        Pengajuan yang telah dikirim akan tampil
                                        pada halaman ini.
                                    </p>

                                    <a
                                        href="{{
                                            route(
                                                'creature-requests.create'
                                            )
                                        }}"
                                        class="btn"
                                        style="margin-top: 14px;"
                                    >
                                        Buat Pengajuan
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div class="pagination">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
