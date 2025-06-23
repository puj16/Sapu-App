@extends('layouts.masterpetugas')



@section('content')

<div class="title-group mb-3">
    <h1 class="h2 mb-0">Overview</h1>   
    <a class="text-muted me-2"> Selamat Datang Petugas PPL ! </a>
</div>
<div class="row my-4">
    <div class="col-lg-6 col-12">

        <div class="custom-block bg-white">
            <h5 class="mb-4">Data Pengajuan</h5>

            <div id="pie-chart"></div>
        </div>

        <div class="custom-block bg-white">
            <h5 class="mb-4">Data Penyaluran</h5>

            <div id="chart1"></div>
        </div>

        {{-- <div class="custom-block bg-white">
            <div id="chart"></div>
        </div> --}}


    </div>

    <div class="col-lg-6 col-12">
        <div class="custom-block custom-block-profile-front custom-block-profile text-center bg-white">
            <div class="custom-block-profile-image-wrap mb-4">
                <img src="{{ asset('storage/assets/doc/profile/'.$user->foto) }}" class="custom-block-profile-image img-fluid" alt="">

                <a  class="bi-pencil-square custom-block-edit-icon" data-bs-toggle="modal" data-bs-target="#editModal{{$user->id}}"></a>
            </div>

            <p class="d-flex flex-wrap mb-2">
                <strong>Name:</strong>

                <span>{{ $user->username }}</span>
            </p>

            <p class="d-flex flex-wrap mb-2">
                <strong>Email:</strong>
                
                <a href="#">
                    {{ $user->email }}
                </a>
            </p>

        </div>

        <div class="custom-block bg-white">
            <h5 class="mb-4">Penyaluran Berhasil</h5>

            <div id="line-chart"></div>
        </div>
    </div>
</div>



<div class="edit">
  <div class="modal fade" id="editModal{{$user->id}}" tabindex="-1" aria-labelledby="editModalLabel{{$user->id}}" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down"> <!-- Tambahkan modal-fullscreen-sm-down -->
          <div class="modal-content">
              <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data Petugas</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <form action="{{route('petugasPPL.update', $user->id)}}" method="post" enctype="multipart/form-data">
                      @csrf  
                      @method('PUT')                                              
                      <div class="row">
                          <!-- Kolom kiri -->
                          <div class="col-md-12 col-12"> <!-- Agar penuh di mobile -->
                              <div class="mb-3">
                                  <label for="username">Username</label>
                                  <input type="text" class="form-control" name="username" id="username" value="{{$user->username }}" readonly>
                              </div>
                              <div class="mb-3">
                                  <label for="email">Email</label>
                                  <input type="text" class="form-control" name="email" id="email" value="{{$user->email }}">
                              </div>
                              <div class="mb-3">
                                  <label for="password">Password</label>
                                  <input type="text" class="form-control" name="password" id="password">
                                  <div class="form-text text-danger">*Kosongi bila tidak ingin mengupdate password</div>
                              </div>
                              <div class="mb-3">
                                  <label for="foto">Foto Profile</label>
                                  <input type="file" class="form-control" name="foto" accept="image/*">
                                  <div class="form-text text-danger">*Kosongi bila tidak ingin mengupdate foto profile</div>
                              </div>
                          </div>

                          <!-- Tombol -->
                          <div class="col-12 text-center mt-3"> <!-- Tombol di tengah di mobile -->
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">Update</button>
                          </div>
                      </div>                                
                  </form>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function() {
      // Ambil data dari Blade Laravel
      var chartData = @json($data);

      var options = {
          series: [chartData.menunggu, chartData.berhasil, chartData.gagal],
          chart: {
              width: 380,
              type: 'pie',
          },
          labels: ['Menunggu Validasi', 'Validasi Berhasil', 'Validasi Gagal'],
          colors: ['#feb019', '#00e396', '#e63946'],
              responsive: [{
              breakpoint: 480,
              options: {
                  chart: {
                      width: 200
                  },
                  legend: {
                      position: 'bottom'
                  }
              }
          }]
      };

      var chart = new ApexCharts(document.querySelector("#pie-chart"), options);
      chart.render();
  });
</script>


<script type="text/javascript">
  var options1 = {
    chart: {
      height: 280,
      type: "radialBar",
    },
    series: @json($progress), // Misalnya: [75, 25]
    labels: @json($label), // Misalnya: ['Tebu', 'Jagung']
    
    plotOptions: {
      radialBar: {
        dataLabels: {
          total: {
            show: true,
            label: 'TOTAL'
          }
        }
      }
    },

    legend: {
      show: true,
      position: 'bottom', // Bisa juga 'top', 'left', 'right'
      horizontalAlign: 'center',
      markers: {
        width: 12,
        height: 12,
        radius: 12 // Bentuk bulat
      },
      fontSize: '14px',
      fontWeight: 500
    },
    title: {
          text: 'Progress Pupuk yang telah disalurkan ke Petani',
          align: 'left'
        }
  };

  new ApexCharts(document.querySelector("#chart1"), options1).render();
</script>


<script type="text/javascript">

        var options = {
          series: {!! $series !!},
          chart: {
          type: 'line',
          height: 350
        },
        stroke: {
          curve: 'smooth',
        },
        dataLabels: {
          enabled: false
        },
        title: {
          text: 'Petani yang telah menerima pupuk',
          align: 'left'
        },
        markers: {
          hover: {
            sizeOffset: 4
          }
        },xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
        }
        };

        var chart = new ApexCharts(document.querySelector("#line-chart"), options);
        chart.render();
</script>



@endpush
