# Modélisation de la base de données

---

Nous avons décidé de mettre la table des évènements dans les bases de données locales. En effet nous pensons que les évènements d’un campus ne sont pas utiles aux autres campus de France. Dans le cas contraire, les utilisateurs peuvent passer par les sites de chaque BDE.



## Tables

> Les tables `user_like`, `user_association` et `user_participation` sont fictives. En réalité elles seront remplacées par l’acquisition des données utilisateurs via l’API. Les tables `user`, `promotion` et `campus` sont stockées sur la base de données nationale.



### user

> Les utilisateurs (stockés sur la base de données nationale).

| Nom        | Type    | Taille | Description                                           |
| ---------- | ------- | ------ | :---------------------------------------------------- |
| last_name  | Varchar | 50     | Nom de famille                                        |
| first_name | Varchar | 50     | Prénom                                                |
| genre      | Varchar | 20     | Le genre de l’utilisateur                             |
| email      | Varchar | 255    | Mail                                                  |
| password   | Varchar | 255    | Mot de passe                                          |
| roles      | Text    | /      | Différents rôles de l’utilisateur (admin, salarié, …) |
| image      | Varchar | 255    | Lien de l’image de la photo de profile                |



### promotion

> Les différentes promotions (stockées sur la base de données nationales).

| Nom  | Type    | Taille | Descriptions           |
| ---- | ------- | ------ | ---------------------- |
| name | Varchar | 10     | Le nom de la promotion |



### event

> Les différents évènements.

| Nom         | Type     | Taille | Description                                    |
| ----------- | -------- | ------ | ---------------------------------------------- |
| name        | Varchar  | 255    | Nom de l’évènement                             |
| date        | Datetime | /      | Date du début                                  |
| description | Text     | /      | Description                                    |
| image       | Varchar  | /      | Lien de l’image décrivant l’évènement          |
| price       | Decimal  | 10, 2  | Prix de l’entrée, deux décimales de précisions |
| duration    | Datetime | /      | Durée de l’évènement                           |



### gallery

> Les photos des évènements.

| Nom   | Type    | Taille | Descriptions       |
| ----- | ------- | ------ | ------------------ |
| image | Varchar | 255    | Le lien de l’image |



### period

> Les cycles de répétition des l’évènements.

| Nom  | Type     | Taille | Descriptions                          |
| ---- | -------- | ------ | ------------------------------------- |
| time | Datetime | /      | Le cycle de répétition de l’évènement |



### campus

> Les différents campus (stockés sur la base de données nationale).

| Nom  | Type    | Taille | Description               |
| ---- | ------- | ------ | ------------------------- |
| name | Varchar | 255    | Nom de la ville du campus |



### association

> Les associations des campus.

| Nom         | Type    | Taille | Description                   |
| ----------- | ------- | ------ | ----------------------------- |
| name        | Varchar | 255    | Nom de l’association          |
| description | Text    | /      | Description                   |
| image       | Varchar | 255    | Lien du logo de l’association |



### product

> Les produits qui seront en ventes dans la boutique.

| Nom         | Type    | Taille | Description                                   |
| ----------- | ------- | ------ | --------------------------------------------- |
| name        | Varchar | 255    | Nom du produit                                |
| description | Text    | /      | Description                                   |
| price       | Decimal | 10, 2  | Prix du produit, deux décimales de précisions |
| stock       | Int     | /      | Nombre de produit restant en stock            |
| image       | Varchar | 255    | Lien de l’image du produit                    |



### category

> Les catégories sont là pour pouvoir classer les produits de la boutique.

| Nom  | Type    | Taille | Description         |
| ---- | ------- | ------ | ------------------- |
| name | Varchar | 255    | Nom de la catégorie |



### comment

> Les commentaires des évènements et des produits du magasin.

| Nom        | Type     | Taille | Description                           |
| ---------- | -------- | ------ | ------------------------------------- |
| content    | Text     | /      | Le contenu du commentaire             |
| created_at | Datetime | /      | La date de publication du commentaire |





## Relations

### user

Un `user` est dans un seul `campus` et une seule `promotion`. 

Un `user` peut liker des photos de la `gallery`, poster des `comment`, participer à des `event`.

Un `user` doit être dans une ou plusieurs `association`.



### promotion

Une `promotion` possède plusieurs `user`.



### event

Un `event` peut posséder des photos de la `gallery`, des `comment` et des inscription des `user`.

Un `event` peut se répéter grâce à une seule `period`.



### gallery

Une photo de la `gallery` est likée par des `user`.

Une photo de la `gallery` est liée à un seul `event`.



### period

Une `period` est liée à un ou plusieurs `event`.



### campus

Un `campus` possède plusieurs `user`.



### association

Une `association` possède au moins un `user`. 

Une `association` peut proposer des `product`.



### product

Un `product` peut posséder des `comment`. 

Un `product` peut être proposé par une seule `association`.

Un `product` est rangé dans une seule `category`.



### category

Une `category` peut posséder des `product`.



### Comment

Un `comment` est liée à un seul `event` ou un seul `product`.

Un `comment` est posté par un seul `user`. 