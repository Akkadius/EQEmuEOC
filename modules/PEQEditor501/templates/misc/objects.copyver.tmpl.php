    <table class="edit_form">
         <tr>
           <td class="edit_form_header">
             Copy Version
           </td>
         </tr>
         <tr>
           <td class="edit_form_content">
             <form name="object_version" method="post" action="index.php?editor=misc&z=<?=$currzone?>&zoneid=<?=$currzoneid?>&action=54">
             Copy version: <br>
             <input type="text" size="7" name="object_version" value="0"> to 
             <input type="text" size="7" name="new_version" value="<?=$objectversion?>">
             <center>
               <input type="submit" value="Submit"></form><br><br>
             </center>
           </td>
         </tr>
       </table>