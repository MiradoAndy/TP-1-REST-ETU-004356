Q6- Après un POST réussi, l'article n'apparait pas dans la liste rechargée puisqu'il n'est jamais dans les 5 premiers qu'on affiche.
Q7- Voici ce qui l'explique dans l'onglet réseau : access-control-allow-origin : http://localhost:8080
Q8. Oui, ça renvoie 201, mais pas le Location. Je l'ajoute à la main avec setHeader. Vérifiez avec curl -i.

Q9. J'ai pris 204 : la suppression a réussi et il n'y a rien à renvoyer, donc pas de corps.

Q10. Un PUT sans auteur doit échouer (422), car il remplace toute la ressource. Un PATCH sans auteur passe, car il ne modifie que ce qui est envoyé. Le code fait la différence avec getMethod().