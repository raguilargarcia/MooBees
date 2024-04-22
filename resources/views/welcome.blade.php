<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Búsqueda de Películas en Español</title>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    /* Estilos para la ventana modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 9999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 600px;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }
  </style>
</head>

<body>

  <h1>Búsqueda de Películas en Español</h1>
  <input type="text" id="search-input" placeholder="Buscar películas...">
  <ul id="movies-list"></ul>

  <!-- Ventana modal para detalles de la película -->
  <div id="myModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2 id="modal-title"></h2>
      <img src="" id="modal-poster" alt="">
      <p id="modal-overview"></p>
      <p id="modal-overview"></p>
      <p id="modal-genres"></p>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      const apiKey = '6e77f3008b5489918d40768636265cbd'; // Reemplaza con tu clave de API de TMDb

      function fetchMovies(query) {
        const apiUrl = `https://api.themoviedb.org/3/search/movie?api_key=${apiKey}&query=${query}&language=es`;

        $.ajax({
          url: apiUrl,
          method: 'GET',
          success: function(response) {
            const movies = response.results;
            const moviesList = $('#movies-list');
            moviesList.empty(); // Limpiar la lista antes de agregar nuevos resultados
            movies.forEach(function(movie) {
              const posterUrl = movie.poster_path ? `https://image.tmdb.org/t/p/w500${movie.poster_path}` : 'https://via.placeholder.com/150'; // URL del póster o un placeholder
              const title = movie.title;
              const overview = movie.overview;
              const movieId = movie.id;

              moviesList.append(`<li><a href="#" data-id="${movieId}" class="movie-link"><img src="${posterUrl}" alt="${title}"><br>${title}</a></li>`);
            });
          },
          error: function(xhr, status, error) {
            console.error('Error al realizar la solicitud:', status, error);
          }
        });
      }

      // Función para obtener detalles de la película y mostrarlos en la ventana modal
      function fetchMovieDetails(movieId) {
        const apiUrl = `https://api.themoviedb.org/3/movie/${movieId}?api_key=${apiKey}&language=es`;

        $.ajax({
          url: apiUrl,
          method: 'GET',
          success: function(response) {
            console.log(response);
            const title = response.title;
            const posterUrl = response.poster_path ? `https://image.tmdb.org/t/p/w500${response.poster_path}` : 'https://via.placeholder.com/150';
            const overview = response.overview;
            const genres = response.genres.map(genre => genre.name).join(', '); // Obtener nombres de los géneros y unirlos en una cadena

            $('#modal-title').text(title);
            $('#modal-poster').attr('src', posterUrl);
            $('#modal-overview').text(overview);
            $('#modal-genres').text('Géneros: ' + genres); // Mostrar los géneros en el modal
          },
          error: function(xhr, status, error) {
            console.error('Error al obtener detalles de la película:', status, error);
          }
        });
      }

      // Manejar la búsqueda en tiempo real al escribir en la barra de búsqueda
      $('#search-input').on('input', function() {
        const query = $(this).val().trim();
        if (query !== '') {
          fetchMovies(query);
        } else {
          // Si la barra de búsqueda está vacía, mostrar películas populares por defecto
          fetchPopularMovies(); // Función para obtener películas populares
        }
      });

      // Mostrar detalles de la película en ventana modal al hacer clic en el enlace
      $('#movies-list').on('click', '.movie-link', function(e) {
        e.preventDefault();
        const movieId = $(this).data('id');
        if (movieId) {
          fetchMovieDetails(movieId);
          $('#myModal').css('display', 'block');
        }
      });

      // Función para cerrar el modal
      function closeModal() {
        $('#myModal').css('display', 'none');
      }

      // Cerrar el modal al hacer clic en la "x"
      $('.close').on('click', closeModal);

      // Cerrar el modal al hacer clic fuera del modal
      $(window).on('click', function(event) {
        if (event.target === $('#myModal')[0]) {
          closeModal();
        }
      });


      // Función para obtener películas populares y mostrarlas
      function fetchPopularMovies() {
        const apiUrl = `https://api.themoviedb.org/3/movie/popular?api_key=${apiKey}&language=es`;

        $.ajax({
          url: apiUrl,
          method: 'GET',
          success: function(response) {
            const movies = response.results;
            const moviesList = $('#movies-list');
            moviesList.empty(); // Limpiar la lista antes de agregar nuevos resultados
            movies.forEach(function(movie) {
              const posterUrl = movie.poster_path ? `https://image.tmdb.org/t/p/w500${movie.poster_path}` : 'https://via.placeholder.com/150'; // URL del póster o un placeholder
              const title = movie.title;
              const overview = movie.overview;
              const movieId = movie.id;

              moviesList.append(`<li><a href="#" data-id="${movieId}" class="movie-link"><img src="${posterUrl}" alt="${title}"><br>${title}</a></li>`);
            });
          },
          error: function(xhr, status, error) {
            console.error('Error al obtener películas populares:', status, error);
          }
        });
      }

      // Mostrar películas populares por defecto al cargar la página
      fetchPopularMovies();
    });
  </script>
</body>

</html>