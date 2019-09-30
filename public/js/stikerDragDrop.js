"use strict";

function initAbilityToDragStiker() {

  document.onmousedown = function(e) {      //событие нажатия на кнопку мыши
    // получаем элемент "поле"
    var field = document.getElementById('field');
    // коорд поля
    var fieldCoords = field.getBoundingClientRect(field); 
    // элемент, на который нажали
    var stikerImage = e.target;              
    
    // если элемент не содержит класс 'stiker'  -  проходим мимо
    if (stikerImage.id != "stikerImage")
      return;     
    
    // отмена нажатия правой клавиши и контекста на элементе
    if(e.button == 2) {
      stikerImage.oncontextmenu = function(e) { 
        return false
      };
      return;
    }   
    // отменяем Drag’n’Drop браузера
    stikerImage.ondragstart = function() {
      return false;
    }


    var coor = stikerImage.getBoundingClientRect(); // получаем координаты елемента относительно окна браузера
    var os_x = e.clientX - coor.left;        // получаем смещение по Х указателя мыши относительно начала координат эелемента
    var os_y = e.clientY - coor.top;         // получаем смещение по Y указателя мыши относительно начала координат эелемента

    // фиксируем элемент относительно окна 
    stikerImage.style.position = 'absolute';          
    stikerImage.style.top = (coor.top - fieldCoords.top)+ 'px';       // задаем начальные координаты У
    stikerImage.style.left = (coor.left - fieldCoords.left) + 'px';     // тоже по X

    let x = os_x;             // координата Х с учетом смещения
    let y = os_y;             // координата Y с учетом смещения


    // событие перемещения мыши
    document.onmousemove = function(ev) {   
      x = ev.clientX - os_x - fieldCoords.left; // координата Х с учетом смещения
      y = ev.clientY - os_y - fieldCoords.top;  // координата Y с учетом смещения
      
      if (x + coor.width / 2 > fieldCoords.width) {
        x = fieldCoords.width - coor.width / 2;
      };                    //не выдвигать элемент за правый край

      if (x < 0 - coor.width + coor.width / 2) {
        x = 0 - coor.width + coor.width / 2;
      };                    // не выдвигать элемент за левый край

      if (y < 0 - coor.height + coor.height / 2) {          //если елемент уперся в верх окна 
        y = 0 - coor.height + coor.height / 2;              //не выдвигать элемент за верхний край
      };

      if (y + coor.height / 2 > fieldCoords.height) {   //если елемент уперся в низ окна 
        y = fieldCoords.height - coor.height / 2;       //не выдвигать элемент за нижний край
      };

      stikerImage.style.left = x + 'px';       // задаем координату Х без смещения для движения элемента 
      stikerImage.style.top = y + 'px';        // тоже по У
    }

    //событие отпускания кнопки мыши
    document.onmouseup = function(e) {
      stikerImage.style.position = 'absolute'; // переводим в абсолютные координаты, чтобы при перемещении страницы объекты перемещались со страницей
      document.onmousemove = null;      //отменяем перемещение элемента                
    }
  }
}

