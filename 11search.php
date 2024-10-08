<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nexer</title>
  <link rel="stylesheet" href="Favorites/favorites.css">
  <style>
     .favorite-item {
      margin: 10px;
      text-align: center;
      display: inline-block;
      vertical-align: top;
      text-decoration: none;
      color: inherit;
      width: 300px; 
    }

    .favorite-item img {
      width: 100%; 
      height: auto;
      display: block;
      margin: 0 auto;
    }

    .box {
      display: grid;
      grid-gap: 40px;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
    }
    @media(max-width: 1024px) {
      .box {
        grid-gap: 30px;
      }
    }

    @media(max-width: 768px) {
      .box {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      }
    }

    #search-input {
      padding: 10px;
      border: none;
      border-radius: 20px; 
      background-color: rgba(255, 255, 255, 0.5); 
      width: 200px; 
      transition: background-color 0.3s;
      outline: none; 
    }

    #search-input:focus {
      background-color: rgba(255, 255, 255, 0.7); 
    }

    .search-icon {
      margin-left: -30px; 
      position: relative;
      top: 3px; 
      color: #666;
    }


    .sub-nav {
      display: flex;
      align-items: center;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <header>
      <div class="netflixLogo">
        <a id="logo" href="6homepage.php"><img src="Movies/Assets/logo.png" alt="Logo Image"></a>
      </div>      
      <nav class="main-nav">                
        <a href="6homepage.php#home">Home</a>
        <a href="6homepage.php#tvShows">TV Shows</a>
        <a href="6homepage.php#movies">Movies</a>
        <a href="8Favorites.php">Favorites</a>
      </nav>
      <nav class="sub-nav">
        <input type="text" id="search-input" placeholder="Search...">
        <span class="search-icon">
          <i class="fas fa-search"></i>
        </span>
        <a href="#"><i class="fas fa-search sub-nav-logo"></i></a>
        <a href="#"><i class="fas fa-bell sub-nav-logo"></i></a>
    
      </nav>     
    </header>
    
    <section class="main-container">
      <div class="location" id="home">
        <h1>Search</h1>
        <div class="box" id="movies-container">
        <a href="The Tale of The Princess Kaguya.php" class="favorite-item"><img src="Movies/cover/1.png" alt="The Tale of The Princess Kaguya"></a>
          <a href="Spirited Away.php" class="favorite-item"><img src="Movies/cover/2.png" alt="Spirited Away"></a>
          <a href="When Marnie Was There.php" class="favorite-item"><img src="Movies/cover/3.png" alt="When Marnie Was There"></a>
          <a href="Grave of the Fireflies.php" class="favorite-item"><img src="Movies/cover/4.png" alt="Grave of the Fireflies"></a>
          <a href="Princess Mononoke.php" class="favorite-item"><img src="Movies/cover/5.png" alt="Princess Mononoke"></a>
          <a href="The Secret World of Arrietty.php" class="favorite-item"><img src="Movies/cover/6.png" alt="The Secret World of Arrietty"></a>
          <a href="Pom Poko.php" class="favorite-item"><img src="Movies/cover/7.png" alt="Pom Poko"></a>
          <a href="Only Yesterday.php" class="favorite-item"><img src="Movies/cover/8.png" alt="Only Yesterday"></a>
          <a href="My Neighbor Totoro.php" class="favorite-item"><img src="Movies/cover/9.png" alt="My Neighbor Totoro"></a>
          <a href="The Wind Rises.php" class="favorite-item"><img src="Movies/cover/10.png" alt="The Wind Rises"></a>
          <a href="Nausicaä of the Valley of the Wind.php" class="favorite-item"><img src="Movies/cover/11.png" alt="Nausicaä of the Valley of the Wind"></a>
          <a href="Ponyo.php" class="favorite-item"><img src="Movies/cover/12.png" alt="Ponyo"></a>
          <a href="Kiki_s Delivery Service.php" class="favorite-item"><img src="Movies/cover/13.png" alt="Kiki's Delivery Service"></a>
          <a href="My Neighbors the Yamadas.php" class="favorite-item"><img src="Movies/cover/14.png" alt="My Neighbors the Yamadas"></a>
          <a href="Porco Rosso.php" class="favorite-item"><img src="Movies/cover/15.png" alt="Porco Rosso"></a>
          <a href="Howl_s Moving Castle.php" class="favorite-item"><img src="Movies/cover/16.png" alt="Howl's Moving Castle"></a>
          <a href="Castle in the Sky.php" class="favorite-item"><img src="Movies/cover/17.png" alt="Castle in the Sky"></a>
          <a href= "Whisper of the Heart.php" class="favorite-item"><img src="Movies/cover/18.png" alt="Whisper of the Heart"></a>
          <a href="The Cat Returns.php" class="favorite-item"><img src="Movies/cover/19.png" alt="The Cat Returns"></a>
          <a href="Ocean Waves.php" class="favorite-item"><img src="Movies/cover/20.png" alt="Ocean Waves"></a>
        </div>
      </div>
    </section>

    <footer>
      <p>&copy; 2024 Nexer, Inc.</p>
      <p>II - BINS</p>
    </footer>
  </div>
  
  <script>
    const movies = document.querySelectorAll('.favorite-item');
    const searchInput = document.getElementById('search-input');
    
    searchInput.addEventListener('input', function(event) {
      const query = event.target.value.toLowerCase();
      
      movies.forEach(function(movie) {
        const title = movie.querySelector('img').alt.toLowerCase();
        
        if (title.includes(query)) {
          movie.style.display = 'inline-block';
        } else {
          movie.style.display = 'none';
        }
      });
    });
    
    searchInput.addEventListener('change', function(event) {
      const query = event.target.value.toLowerCase();
      
      if (query === '') {
        movies.forEach(function(movie) {
          movie.style.display = 'inline-block';
        });
      }
    });
  </script>
</body>
</html>
