    <div id="toolBar">
      
      <select onchange="formatDoc('fontsize',this[this.selectedIndex].value);this.selectedIndex=0;">
        <option class="heading" selected>- الحجم -</option>
        <option value="3">خط متوسط</option>
        <option value="4">خط كبير</option>
        <option value="5">خط كبير جدا</option>
      </select>

      <div class="toolInp" ><img src="../img/icon/foreColor.png" /><input type="color" onchange="formatDoc('foreColor', this.value);"></div>
      <div class="toolInp" ><img src="../img/icon/hiliteColor.png" /><input type="color" onchange="formatDoc('backcolor', this.value);"></div>

      <img class="intLink" title="Undo" onclick="formatDoc('undo');" src="../img/icon/undo.png" />
      <img class="intLink" title="Redo" onclick="formatDoc('redo');" src="../img/icon/redo.png" />
      <img class="intLink" title="Bold" onclick="formatDoc('bold');" src="../img/icon/bold.png" />
      <img class="intLink" title="Italic" onclick="formatDoc('italic');" src="../img/icon/italic.png" />
      <img class="intLink" title="strikeThrough" onclick="formatDoc('strikeThrough');" src="../img/icon/strikethrough.png" />
      <img class="intLink" title="underline" onclick="formatDoc('underline');" src="../img/icon/underline.png" />
      <img class="intLink" title="justifyFull" onclick="formatDoc('justifyFull');" src="../img/icon/justifyFull.png" />
      <img class="intLink" title="Right align" onclick="formatDoc('justifyright');" src="../img/icon/justifyRight.png" />
      <img class="intLink" title="Center align" onclick="formatDoc('justifycenter');" src="../img/icon/justifyCenter.png" />
      <img class="intLink" title="Left align" onclick="formatDoc('justifyleft');" src="../img/icon/justifyLeft.png" />
      <img class="intLink" title="Add image" onclick="formatDoc('insertImage', prompt('أدخل رابط الصورة', ''));" src="../img/icon/insertImage.png" />
      <img class="intLink" title="Hyperlink" onclick="var sLnk=prompt('أدخل الرابط هنـا','http:\/\/');if(sLnk&&sLnk!=''&&sLnk!='http:\/\/'){formatDoc('createlink',sLnk)}" src="../img/icon/createLink.png" />
      <img class="intLink" title="unlink" onclick="formatDoc('unlink');" src="../img/icon/unlink.png" />
      <img class="intLink" title="Cut" onclick="formatDoc('cut');" src="../img/icon/cut.png" />
      <img class="intLink" title="Copy" onclick="formatDoc('copy');" src="../img/icon/copy.png" />
      <img class="intLink" title="selectAll" onclick="formatDoc('selectAll');" src="../img/icon/selectAll.png" />
    
    </div><!--- end tools bar --->