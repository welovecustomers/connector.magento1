# Module WeLoveCustomers pour Magento1

### Pré-requis
 - Accès SSH
 - [modman](https://github.com/colinmollenhour/modman) 
 
### Installation
Autoriser les symlinks à partir de votre backoffice Magento1 ("Allow Symlinks" dans Enable "Allow Symlinks" dans System > Configuration > Advanced > Developer > Template Settings).

Ouvrez un terminal et executez les commandes suivantes : 
```
    cd $INSTALL_DIR
    modman init
    modman clone https://github.com/welovecustomers/connector.magento1
    modman deploy
    modman clean
```

Allez sur l'écran de gestion du cache (System > Cache Storage Management). Cliquez sur "Flush Magento Cache" et "Flush Cache Storage". 

 Déconnectez vous puis reconnecter vous au backoffice.
 
 ### Renseigner les clés api
 Connectez-vous à la plateforme [WeLoveCustomers](https://app.welovecustomers.fr/). 
 Allez dans le menu **Profil/[Votre prénom]**. 
 Dans l'onglet API, vous trouvez les clés nécessaires au bon fonctionnement du module.
 
 Pour cela, connectez vous l'administration de votre site Magento2 puis allez dans le menu **System>Configuration>WeLoveCustomers**. Renseignez les champs "Api Key" et "Api Glue" et enregistrez.