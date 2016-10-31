# CakePHP3-Media

Tout d'abord, ce plugin CakePHP est une adaptation du plugin GrafikArt (CakePHP2).

Le principe de ce plugin CakePHP est de vous permettre de créer une gestion d'image poussée pour vos contenu avec le moins de code possible. Le système repose sur un behaviour pour gérer l'association des média aux models et un helper pour gérer la partie administration des médias.

Documentation: http://anouarcharif.github.io/CakePHP3-Media/index.html

N.B: Je viens de mettre à jour le plugin, désormais il faut appeler le plugin comme ça (ne n'ai pas encore eu le temps de mettre à jour la documentation aussi):

// TABLES EN Pluriel au lieu du singuler
echo $this->Media->iframe('TABLES',ID);
