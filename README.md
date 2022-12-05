# Contexte
Ce projet est le premier de votre formation. Il doit vous permettre de mettre à profit
les compétences abordées depuis la rentrée et pourra notamment être intégré à
votre dossier professionnel de formation. 🎓
Le projet court du16 novembre soir au 1er décembre soir et doit être réalisé seul ou
en binôme

# La mission
Votre mission, si toutefois, vous l'acceptez 😀, est de développer une api dotée d'un
système d'authentification pour une application PHP
L’intérêt de l’authentification est de pouvoir restreindre l’accès à un service et de
garantir un contrôle de l’identité des utilisateurs.
Les fonctionnalités attendues sont donc les suivantes:
1. L’API doit permettre à un utilisateur de s’authentifier. L’authentification doit
protéger des pages qui devront être inaccessibles tant que l’utilisateur n’est pas
authentifié.
2. Elle doit offrir un accès sécurisé au service, en fonction du rôle de l’utilisateur
Trois rôles sont possibles:
admin: accès à toutes les pages avec droits d'ajout, suppression,
modification
user: accès aux pages user et guest avec droits de modification (update)
guest: accès aux pages publiques (guest?) avec droits de lecture seulement.
Pas d'accès aux pages user et admin.
3. Le système d’authentification doit être limité dans le temps. Un délai doit être
fixé au déla duquel la page n’est plus accessible (par copier-coller du lien ou
rafraichissement de la page)
4. Une interface d’administration du site permet de gérer les utilisateurs
