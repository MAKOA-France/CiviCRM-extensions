

<h3>Détail du groupe {$titregroupe} </h3>

{* Example: Display a variable directly *}
<h4>Description :</h4><p> {$detailgroupe}</p>


<h3>Membres du groupe </h3>

 <ul>
{foreach from=$lesmembres item=onemembre}
  <li>{$onemembre}</li>
  {foreachelse}
  <li>Aucun élément n'a été trouvé dans la recherche </li>
{/foreach}
</ul> 

<a href="mes-groupes">Retour</a>