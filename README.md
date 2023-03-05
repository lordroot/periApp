# periApp
Application qui date de 2016 qui me permettait de faire un stream sur periscope / liker / commenter

Ce projet permet d'interagir avec l'API interne de Periscope en PHP, en utilisant des fonctionnalités telles que CURL, websockets, l'API Twitter, DOMXPATH pour le parsing, et ffmpeg pour la transmission de flux vidéo.
Utilisation des technologies
CURL

CURL est utilisé pour communiquer avec l'API de Periscope. CURL est une bibliothèque de transfert de données qui prend en charge de nombreux protocoles différents. Avec CURL, nous pouvons envoyer des requêtes HTTP et HTTPS à l'API de Periscope pour récupérer des informations sur les utilisateurs, les diffusions en direct, et plus encore.
Websockets

Les websockets sont utilisées pour communiquer en temps réel avec l'API de Periscope. Les websockets permettent une communication bidirectionnelle entre le serveur et le client, ce qui est très utile pour les applications en temps réel comme Periscope. Les websockets permettent également de récupérer des données en continu, ce qui est important pour les applications de diffusion en direct.
API Twitter

L'API Twitter est utilisée pour partager des diffusions en direct sur Twitter. Nous utilisons les clés d'API et les jetons d'accès Twitter pour nous connecter à l'API Twitter et publier des tweets qui contiennent des liens vers les diffusions en direct.
DOMXPATH

DOMXPATH est utilisé pour extraire des données à partir de pages HTML. Nous utilisons DOMXPATH pour extraire des informations sur les diffusions en direct et les utilisateurs à partir des pages HTML renvoyées par l'API de Periscope.
FFmpeg

FFmpeg est utilisé pour la transmission de flux vidéo en direct. Nous utilisons FFmpeg pour encoder le flux vidéo et l'envoyer à l'API de Periscope. FFmpeg est une bibliothèque de traitement multimédia très populaire et puissante.

Le code à présent doit être obselète.
