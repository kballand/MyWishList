# **MyWishList**

## Qu'est-ce que c'est ?

MyWishList est un projet étudiant du département Informatique de l'IUT Nancy Charlemagne, celui-ci consiste à développer
un site web en HTML/CSS à l'aide du PHP côté serveur.

Voici un descriptif du sujet plus appronfondi : **[Descriptif](https://arche.univ-lorraine.fr/pluginfile.php/1498754/mod_resource/content/0/details-projet-wishlist_2018.pdf)**.

Comme indiqué dans le descriptif le projet est décomposé en plusieurs fonctionnalités (voir *[ci-dessous](https://github.com/kballand/MyWishList/new/master?readme=1#fonctionnalit%C3%A9s)*).

Si vous êtes intéréssé par l'utilisation de ce projet, vous pouvez directement passer à la partie *[installation](https://github.com/kballand/MyWishList/new/master?readme=1#installation)*.

## Fonctionnalités

#### Participant
- [x] ~~**1. Afficher une liste de souhaits _[FAIT @Killian]_**~~
- [x] ~~**2. Afficher un item d'une liste _[FAIT @Killian]_**~~
- [x] ~~**3. Réserver un item _[FAIT @Guillaume]_**~~
- [x] ~~**4. Ajouter un message lors de la validation du formulaire _[FAIT @Louis]_**~~
- [x] ~~**5. Permettre d'ajouter un message public rattaché à la liste _[FAIT @Louis]_**~~

#### Créateur
- [x] ~~**6. Créer une liste _[FAIT @Antoine]_**~~
- [x] ~~**7. Modifier les informations générales d'une de ses listes _[FAIT @Antoine]_**~~
- [x] ~~**8. Ajouter des items _[FAIT @Killian]_**~~
- [x] ~~**9. Modifier un item _[FAIT @Killian]_**~~
- [x] ~~**10. Supprimer un item _[FAIT @Killian]_**~~
- [x] ~~**11. Rajouter une image à un item (hot-linking) _[FONCTIONNEL SAUF SUR WEBETU CAR NON AUTORISE @Guillaume]_**~~
- [x] ~~**12. Modifier une image d'un item _[FAIT @Guillaume]_**~~
- [x] ~~**13. Supprimer une image d'un item _[FAIT @Killian]_**~~
- [x] ~~**14. Partager une liste _[FAIT @Louis]_**~~
- [x] ~~**15. Consulter les réservations d'une de ses listes avant échéance _[FAIT @Louis]_**~~
- [x] ~~**16. Consulter les réservations et messages d'une de ses listes après échance _[FAIT @Antoine]_**~~

#### Extensions
- [x] ~~**17. Créer un compte _[FAIT @Killian]_**~~
- [x] ~~**18. S'authentifier _[FAIT @Killian]_**~~
- [x] ~~**19. Modifier son compte _[FAIT @Louis]_**~~
- [x] ~~**20. Rendre une liste publique _[FAIT @Antoine @Guillaume]_**~~
- [x] ~~**21. Afficher les listes de souhaits publiques _[FAIT @Antoine @Guillaume]_**~~
- [ ] **22. Créer une cagnotte sur un item _[A FAIRE]_**
- [ ] **23. Participer à une cagnotte _[A FAIRE]_**
- [x] ~~**24. Uploader une image _[FAIT @Guillaume]_**~~
- [x] ~~**25. Créer un compte participant _[FAIT @Killian]_**~~
- [x] ~~**26. Afficher la liste des créateurs _[FAIT @Antoine]_**~~
- [x] ~~**27. Supprimer son compte _[FAIT @Killian]_**~~
- [x] ~~**28. Joindre des listes à son compte _[FAIT @Louis]_**~~

#### Bonus
- [x] ~~**29. Menu déroulants _[JQUERY]_**~~
- [x] ~~**30. Barre de navigation collante _[JQUERY]_**~~
- [x] ~~**31. Popup _[JQUERY]_**~~
- [x] ~~**32. Déconnection de son compte _[PHP]_**~~
- [x] ~~**33. Afficheur d'erreurs (champs) _[JQUERY]_**~~
- [x] ~~**34. Preview d'image _[JQUERY]_**~~
- [x] ~~**35. Création de compte en plusieurs parties _[JQUERY]_**~~
- [x] ~~**36. Copieur de texte dans le presse-papier _[JQUERY]_**~~

## Installation

### Préliminaires

Téléchargez tout d'abord l'**[archive](https://github.com/kballand/MyWishList/archive/master.zip)** du projet sur le dépot.

Une fois l'archive extraite placer le projet à l'endroit où vous souhaitez que le site soit accessible sur votre serveur.

### Mise en place de la BDD

Une fois que les préléminaires sont terminés, il vous faut mettre en place la base de données du site.

Pour ce faire vous devez ouvrir votre SGBD (Système de Gestion de Base de Données) tel que *MySQL* (de préférence)
et créer une nouvelle base de données qui dans notre cas s'appelera **_toto_**.

Une fois ceci fait vous devez accèder au menu d'importation/exécution de fichiers SQL de votre base de données et ensuite
éxécuter le fichier de données du site [*mywishlist*](sql/mywishlist.sql) qui se trouve dans le répertoire [*sql*](sql).

### Liaison du site à la BDD

Après avoir mise en place votre BDD il faut faire la liaison entre celui-ci et le site web.

Pour celà rendez-vous dans le fichier [*conf.ini*](src/conf/conf.ini) qui se trouve dans le répertoire [*src/conf*](src/conf).

Dans ce fichier il va falloir que vous modifiez ses champs en fonction des informations de votre base de données c'est à dire :

```ini
driver = <Votre SGBD (MySQL, Oracle)...>
username = <Votre nom d utilisateur pour vous connecter à votre SGBD>
password = <Votre mot de passe pour vous connecter à votre SGBD>
host = <Adresse de votre server ou 'localhost' en local>
database = <Le nom de la base de donnée crée précemment (toto pour nous)>
charset = utf8    # Pas besoin de modifier
```

La liason entre votre site et la base de données et donc finalement faite.

La prochaine étape est optionnelle est dépend de l'emplacement du projet par rapport à votre serveur. Si celui-ci
est à la racine de votre serveur pas besoin de la suivre.

### *\[OPTIONNEL\]* Adapter le .htaccess

Si le site ne se trouve pas dans la racine de votre serveur, cette partie partie vous concerne, sinon vous êtes déjà
arrivés à la fait de l'installation du site web (facile non ?).

Cette manipulation est assez complexe et nécessite que vous calculiez le chemin absolu entre la racine de votre serveur
et l'emplacement du site web.

Prenons un exemple :

> Si la racine de votre serveur est **_~/MesFichiers/MonServeur_** et que le site web se trouve dans
**_~/MesFichiers/MonServeur/SubDir/MyWishList_**, alors le chemin absolue du site par rapport au serveur sera
**_/MonServeur/SubDir/MyWishList_**

Une fois votre chemin absolu calculé, rendez vous dans le fichier *[.htaccess](.htaccess)* à la racine du *[projet](.)*.

Et remplacez donc la ligne :

```.htaccess
# RewriteBase /balland64u/www/MyWishList
```

par :

```ApacheConf
RewriteBase votreCheminAbsolu
```

Si tout c'est bien passé votre configuration devrait être terminé et l'installation aussi.

# Auteurs

- **BALLAND Killian** - étudiant en 2ème année (S3D) à l'IUT Nancy Charlemagne section Informatique.
- **GACHENOT Antoine** - étudiant en 2ème année (S3D) à l'IUT Nancy Charlemagne section Informatique.
- **RISSE Guillaume** - étudiant en 2ème année (S3D) à l'IUT Nancy Charlemagne section Informatique.
- **MATUCHET Louis** - étudiant en 2ème année (S3D) à l'IUT Nancy Charlemagne section Informatique.

# Remerciements

#### Merci d'avoir lu ce README jusqu'au bout, malheuresement il n'y a rien à gagner pour cet exploit :blush:
