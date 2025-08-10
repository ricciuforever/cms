<style>
/* Font e colori moderni */
body {
  font-family: 'Roboto', sans-serif;
  font-size: 16px;
  line-height: 1.6;
  background-color: #f9f9f9;
  color: #333;
  margin: 0;
  padding: 0;
}

a {
  color: #e63946;
  text-decoration: none;
  transition: color 0.3s ease;
}

a:hover {
  color: #d62828;
}

/* Wrapper centrale */
#wrapper {
  max-width: 1200px;
  margin: 0 auto;
  background-color: #fff;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  padding: 20px;
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

/* Header */
#top {
  background-color: #f1f1f1;
  padding: 20px;
  text-align: center;
  border-bottom: 2px solid #e63946;
  width: 100%;
}

#top h1 {
  font-size: 24px;
  color: #e63946;
}

/* Sidebar */
#sidebar {
  background-color: #f8f8f8;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  flex: 1 1 30%;
  min-width: 250px;
}

#sidebar h3 {
  background-color: #e63946;
  color: #fff;
  padding: 10px;
  border-radius: 4px;
  text-align: center;
}

/* Contenuto principale */
#content {
  flex: 1 1 65%;
  min-width: 300px;
}

#content h1, #content h2, #content h3 {
  color: #e63946;
  margin-bottom: 10px;
}

#content p {
  margin-bottom: 15px;
}

/* Footer */
#footer {
  text-align: center;
  background-color: #333;
  color: #fff;
  padding: 10px;
  border-radius: 0 0 8px 8px;
  width: 100%;
}

/* Pulsanti */
button, .btn {
  background-color: #e63946;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

button:hover, .btn:hover {
  background-color: #d62828;
}

/* Effetti hover */
.elenconumeri .elenconumero {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.elenconumeri .elenconumero:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Media query per dispositivi mobili */
@media (max-width: 768px) {
  #wrapper {
    flex-direction: column;
  }

  #sidebar, #content {
    width: 100%;
    margin: 0;
  }

  #top h1 {
    font-size: 20px;
  }
}
</style>