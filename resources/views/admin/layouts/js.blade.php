 <script src="{{ asset('js/plugin/webfont/webfont.min.js') }}"></script>
 <script>
     WebFont.load({
         google: {
             "families": ["Public Sans:300,400,500,600,700"]
         },
         custom: {
             "families": ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands",
                 "simple-line-icons"
             ],
             urls: ["{{ asset('css/fonts.min.css') }}"]
         },
         active: function() {
             sessionStorage.fonts = true;
         }
     });
 </script>
 <!--   Core JS Files   -->
 <script src="{{ asset('js/core/jquery-3.7.1.min.js') }}"></script>
 <script src="{{ asset('js/core/popper.min.js') }}"></script>
 <script src="{{ asset('js/core/bootstrap.min.js') }}"></script>

 <!-- jQuery Scrollbar -->
 <script src="{{ asset('js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

 <!-- Chart JS -->
 <script src="{{ asset('js/plugin/chart.js/chart.min.js') }}"></script>

 <!-- jQuery Sparkline -->
 <script src="{{ asset('js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

 <!-- Chart Circle -->
 <script src="{{ asset('js/plugin/chart-circle/circles.min.js') }}"></script>

 <!-- Datatables -->
 <script src="{{ asset('js/plugin/datatables/datatables.min.js') }}"></script>

 <!-- Bootstrap Notify -->
 <script src="{{ asset('js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

 <!-- jQuery Vector Maps -->
 <script src="{{ asset('js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
 <script src="{{ asset('js/plugin/jsvectormap/world.js') }}"></script>

 <!-- Sweet Alert -->
 <script src="{{ asset('js/plugin/sweetalert/sweetalert.min.js') }}"></script>

 <!-- Kaiadmin JS -->
 <script src="{{ asset('js/kaiadmin.min.js') }}"></script>

 {{-- Select2 --}}
 <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

 <script>
     $('#lineChart').sparkline([102, 109, 120, 99, 110, 105, 115], {
         type: 'line',
         height: '70',
         width: '100%',
         lineWidth: '2',
         lineColor: '#177dff',
         fillColor: 'rgba(23, 125, 255, 0.14)'
     });

     $('#lineChart2').sparkline([99, 125, 122, 105, 110, 124, 115], {
         type: 'line',
         height: '70',
         width: '100%',
         lineWidth: '2',
         lineColor: '#f3545d',
         fillColor: 'rgba(243, 84, 93, .14)'
     });

     $('#lineChart3').sparkline([105, 103, 123, 100, 95, 105, 115], {
         type: 'line',
         height: '70',
         width: '100%',
         lineWidth: '2',
         lineColor: '#ffa534',
         fillColor: 'rgba(255, 165, 52, .14)'
     });
 </script>
 <script>
     @if ($message = Session::get('success'))
         swal({
             title: "{{ $message['title'] }}",
             text: "{{ $message['message'] }}",
             icon: "success",
             buttons: false,
             timer: 2000,
         });
     @endif
     @if ($message = Session::get('error'))
         swal({
             title: "{{ $message['title'] }}",
             text: `{!! $message['message'] !!}`,
             icon: "error",
             buttons: {
                 confirm: {
                     className: 'btn btn-danger'
                 },
             }

         });
     @endif
 </script>
 @yield('js')
