@extends('layouts.template')

@section('content')
    <div class="container mt-3">
        <div class="d-flex justify-content-between my-3">
            <div class="row w-100 ml-2">
                <form action="{{ route('kasir.order.search') }}" method="get">
                    <div class="col-6">
                        <input  type="date" name="search" id="search" class="form-control">
                        <div class="col-4 d-inline">
                            <button class="btn btn-info" id="cari_data">Cari Data</button>
                            <button class="btn btn-secondary" id="clear_data"><a href="{{ route('kasir.order.index') }}" ></a>Clear</button>
                        </div>
                    </div>
                </form>
            </div>

            <a href="{{ route('kasir.order.create') }}" class="btn btn-primary">Pembelian Baru</a>
        </div>

        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <td class="text-center">No</td>
                    <th>Pembeli</th>
                    <th>Obat</th>
                    <th>Total Bayar</th>
                    <th>Kasir</th>
                    <th>Tanggal Beli</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name_customer }}</td>
                        <td>
                            @foreach ($item->medicines as $medicine)
                                <ol>
                                    <li>
                                        {{ $medicine['name_medicine'] }} ({{ number_format($medicine['price'],0,',','.') }})
                                        : Rp. {{ number_format($medicine['sub_price'],0,',','.') }}
                                        <small>qty {{ $medicine['qty'] }}</small>
                                    </li>
                                </ol>
                            @endforeach
                        </td>
                        <td>
                            {{ number_format($item->total_price, 0, ',', '.') }}
                        </td>
                        <td>
                            {{ $item['name'] }}
                        </td>
                        <td>
                            @php \Carbon\Carbon::setlocale('id_ID') @endphp
                            {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('D, d F Y') }}
                            <br>
                            {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                        </td>
                        <td>
                            <a href="{{ route('kasir.order.download', $item->id) }}" class="btn btn-secondary">Download Struk</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            @if ($orders->count())
                {{ $orders->links() }}
            @endif
        </div>  
    </div>
@endsection