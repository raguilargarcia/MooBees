$(document).ready(function () {
  const apiKey = '6e77f3008b5489918d40768636265cbd'; // Tu clave de API de TMDb

  function fetchMovies(query, page) {
    const apiUrl = `https://api.themoviedb.org/3/search/movie?api_key=${apiKey}&query=${query}&page=${page}&limit=20&language=es`;

    $.ajax({
      url: apiUrl,
      method: 'GET',
      success: function (response) {
        const movies = response.results;
        const moviesList = $('#movies-list');
        moviesList.empty(); // Limpiar la lista antes de agregar nuevos resultados
        movies.forEach(function (movie) {
          const posterUrl = movie.poster_path ? `https://image.tmdb.org/t/p/w500${movie.poster_path}` : 'https://via.placeholder.com/150'; // URL del póster o un placeholder
          const title = movie.title;
          const movieId = movie.id;

          moviesList.append(`<li><a href="#" data-id="${movieId}" class="movie-link"><img src="${posterUrl}" alt="${title}"><br>${title}</a></li>`);
        });

        // Redirigir a la página de búsqueda después de obtener los resultados
        if (query !== '') {
          const queryWithoutSpaces = query.trim().replace(/\s+/g, '-'); // Reemplazar espacios en blanco con guiones
          const searchUrl = `/search/${queryWithoutSpaces}/${page}`;
          window.location.href = searchUrl;
        }

      },
      error: function (xhr, status, error) {
        console.error('Error al realizar la solicitud:', status, error);
      }
    });
  }

  $('#movies-list').on('click', 'li', function (event) {
    event.preventDefault(); // Evitar el comportamiento predeterminado del enlace

    const movieId = $(this).data('id');
    const movieUrl = `/peliculas/${movieId}`; // Construir la URL de la película

    window.location.href = movieUrl;
  });

  // Manejar la búsqueda al hacer clic en el botón de búsqueda
  $('#button-search').on('click', function () {
    const query = $('#search-input').val().trim();
    if (query !== '') {
      fetchMovies(query, 1);
      // Ocultar la lista de películas
      $('#movies-list').empty();
    } else {
      // Si la barra de búsqueda está vacía, mostrar la lista de películas
      fetchPopularMovies(); // Función para obtener películas populares
      fetchTrendingMovies(); // Función para obtener películas en tendencia
    }
  });



//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// LLAMAR A LA FUNCIÓN PARA OBTENER PELÍCULAS POPULARES

function fetchPopularMovies() {
  const apiUrl = `https://api.themoviedb.org/3/movie/popular?api_key=${apiKey}&language=es`;

  $.ajax({
    url: apiUrl,
    method: 'GET',
    success: function (response) {
      const movies = response.results;
      console.log(movies);
      initPopularCarousel(movies); // Inicializar el carrusel de películas populares
    },
    error: function (xhr, status, error) {
      console.error('Error al obtener películas populares:', status, error);
    }
  });

  $('#popular-list').on('click', '.movie-link', function (event) {
    event.preventDefault(); // Evitar el comportamiento predeterminado del enlace

    const movieId = $(this).data('id');
    const movieUrl = `/peliculas/${movieId}`; // Construir la URL de la película

    window.location.href = movieUrl;
  });
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// INICIALIZAR EL CARRUSEL DE PELÍCULAS POPULARES

function initPopularCarousel(movies) {
  const popularCarouselInner = $('#popular-carousel .carousel-inner');
  popularCarouselInner.empty(); // Limpiar el carrusel antes de agregar nuevas imágenes

  let activeClass = 'active'; // Inicializar la clase active para la primera diapositiva

  for (let i = 0; i < movies.length; i += 5) {
    const movieSlice = movies.slice(i, i + 5); // Obtener un grupo de 5 películas
    const carouselItems = $('<div class="carousel-item">'); // Crear una nueva diapositiva

    const row = $('<div class="row">'); // Crear una fila para las imágenes

    // Agregar cada película al grupo de la diapositiva actual
    movieSlice.forEach(function (movie) {
      const posterUrl = movie.poster_path ? `https://image.tmdb.org/t/p/w500${movie.poster_path}` : 'https://via.placeholder.com/150'; // URL del póster o un placeholder
      const title = movie.title;
      const movieId = movie.id;

      // Agregar la imagen con un enlace a la ficha informativa de la película
      const imageLink = $(`
        <div class="col-md-2">
          <a href="/peliculas/${movieId}" class="movie-link">
            <img src="${posterUrl}" class="d-block w-100" alt="${title}">
          </a>
        </div>
      `);

      row.append(imageLink); // Agregar la imagen al contenedor de la fila
    });

    carouselItems.append(row); // Agregar la fila al grupo de la diapositiva
    carouselItems.addClass(activeClass); // Agregar la clase active a la primera diapositiva

    popularCarouselInner.append(carouselItems); // Agregar el grupo de la diapositiva al carrusel

    // Cambiar la clase active para las siguientes diapositivas
    activeClass = '';
  }

  function avanzarCarrusel() {
    $('#popular-carousel').carousel('next'); // Avanzar a la siguiente diapositiva
  }

  // Configurar intervalo para avanzar automáticamente cada 3 segundos (3000 milisegundos)
  setInterval(avanzarCarrusel, 5000); // Cambiar el valor según la velocidad deseada
  
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// LLAMAR A LA FUNCIÓN PARA OBTENER PELÍCULAS EN TENDENCIA

function fetchTrendingMovies() {
  const apiUrl = `https://api.themoviedb.org/3/trending/movie/day?api_key=${apiKey}&language=es`;

  $.ajax({
    url: apiUrl,
    method: 'GET',
    success: function (response) {
      const movies = response.results;
      console.log(movies);
      initTrendingCarousel(movies); // Inicializar el carrusel de películas en tendencia
    },
    error: function (xhr, status, error) {
      console.error('Error al obtener películas en tendencia:', status, error);
    }
  });

  $('#trending-list').on('click', '.movie-link', function (event) {
    event.preventDefault(); // Evitar el comportamiento predeterminado del enlace

    const movieId = $(this).data('id');
    const movieUrl = `/peliculas/${movieId}`; // Construir la URL de la película

    window.location.href = movieUrl;
  });
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// INICIALIZAR EL CARRUSEL DE PELÍCULAS EN TENDENCIA

function initTrendingCarousel(movies) {
  const trendingCarouselInner = $('#trending-carousel .carousel-inner');
  trendingCarouselInner.empty(); // Limpiar el carrusel antes de agregar nuevas imágenes

  let activeClass = 'active'; // Inicializar la clase active para la primera diapositiva

  for (let i = 0; i < movies.length; i += 5) {
    const movieSlice = movies.slice(i, i + 5); // Obtener un grupo de 5 películas
    const carouselItems = $('<div class="carousel-item">'); // Crear una nueva diapositiva

    const row = $('<div class="row">'); // Crear una fila para las imágenes

    // Agregar cada película al grupo de la diapositiva actual
    movieSlice.forEach(function (movie) {
      const posterUrl = movie.poster_path ? `https://image.tmdb.org/t/p/w500${movie.poster_path}` : 'https://via.placeholder.com/150'; // URL del póster o un placeholder
      const title = movie.title;
      const movieId = movie.id;

      // Agregar la imagen con un enlace a la ficha informativa de la película
      const imageLink = $(`
        <div class="col-md-2">
          <a href="/peliculas/${movieId}" class="movie-link">
            <img src="${posterUrl}" class="d-block w-100" alt="${title}">
          </a>
        </div>
      `);

      row.append(imageLink); // Agregar la imagen al contenedor de la fila
    });

    carouselItems.append(row); // Agregar la fila al grupo de la diapositiva
    carouselItems.addClass(activeClass); // Agregar la clase active a la primera diapositiva

    trendingCarouselInner.append(carouselItems); // Agregar el grupo de la diapositiva al carrusel

    // Cambiar la clase active para las siguientes diapositivas
    activeClass = '';
  }

  function avanzarCarrusel() {
    $('#trending-carousel').carousel('next'); // Avanzar a la siguiente diapositiva
  }

  // Configurar intervalo para avanzar automáticamente cada 3 segundos (3000 milisegundos)
  setInterval(avanzarCarrusel, 5000); // Cambiar el valor según la velocidad deseada
}

// Llamar a las funciones para obtener y mostrar películas populares y en tendencia
fetchPopularMovies();
fetchTrendingMovies();

});