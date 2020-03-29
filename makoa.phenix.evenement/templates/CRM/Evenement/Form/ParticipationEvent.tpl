{* HEADER *}

 <div>{$message}</div>

 <div>{$introtext}</div>

 <br>
 <br>
 <h3>Vos informations personnelles </h3>
 <div> {$first_name} </div>
 </br>

 <div> {$last_name} </div>
 </br>

 <div> {$email} </div>
 </br>

{* FIELD EXAMPLE: OPTION 1 (AUTOMATIC LAYOUT) *}

<table  border="1">
{foreach from=$elementNames item=elementName}
<tr>
  <td> <div class="label">{$form.$elementName.label}  </div></td>
  <td> <div class="content">{$form.$elementName.html} </div></td>
</tr>
{/foreach}
</table>
{if ($showbutton)}
<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
{/if}
<br>

{if $rows}
    {include file="CRM/common/pager.tpl" location="top"}
       <table cellpadding="0" cellspacing="0" border="0">
         <tr class="columnheader">
        {foreach from=$headers item=header}
        <th scope="col">
        {if $header.sort}
          {assign var='key' value=$header.sort}
          {$sort->_response.$key.link}
        {else}
          {$header.name}
        {/if}
        </th>
      {/foreach}
         </tr>
      {foreach from=$rows item=row}
         <tr class="{cycle values="odd-row,even-row"}">
            <td class="crm-participant-name">{$row.name}</td>
            <td class="crm-participant-role">{$row.participantStatus}</td>
            
         </tr>
      {/foreach}
      </table>
    {include file="CRM/common/pager.tpl" location="bottom"}
{else}
    <!-- <div class='spacer'></div>
    <div class="messages status no-popup">
      <div class="icon inform-icon"></div>
        {ts}{*There are currently no participants registered for this event.*}{/ts}
    </div> -->
{/if}
