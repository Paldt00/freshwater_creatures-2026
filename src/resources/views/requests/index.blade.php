@extends('layouts.public')

@section('title', 'Request Saya')

@section('content')
<div class="container">
    <div class="section-head">
        <div>
            <h2>Request Saya</h2>
            <p>Riwayat pengajuan penambahan dan perubahan data ikan air tawar.</p>
        </div>

        <a href="{{ route('creature-requests.create') }}" class="btn">
            Ajukan Data
        </a>
    </div>

    <div class="table-card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Ikan</th>
                        <th>Jenis Request</th>
                        <th>Wilayah</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Catatan Admin</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($requests as $request)
                        <tr>
                            <td>
                                <strong>{{ $request->name }}</strong>

                                @if($request->scientific_name)
                                    <br>
                                    <small>
                                        <em>{{ $request->scientific_name }}</em>
                                    </small>
                                @endif
                            </td>

                            <td>
                                @if($request->request_type === 'add')
                                    <span class="badge">
                                        Tambah Data
                                    </span>
                                @else
                                    <span class="badge">
                                        Perubahan Data
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $request->region?->name ?? '-' }}
                            </td>

                            <td>
                                {{ $request->category?->name ?? '-' }}
                            </td>

                            <td>
                                <span class="badge {{ $request->request_status }}">
                                    @if($request->request_status === 'pending')
                                        Pending
                                    @elseif($request->request_status === 'approved')
                                        Approved
                                    @elseif($request->request_status === 'rejected')
                                        Rejected
                                    @else
                                        {{ ucfirst($request->request_status) }}
                                    @endif
                                </span>
                            </td>

                            <td>
                                {{ $request->admin_note ?: '-' }}
                            </td>

                            <td>
                                {{ $request->created_at?->format('d M Y H:i') ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty">
                                    Belum ada request yang dikirim.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection
