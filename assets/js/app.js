const $ = require('jquery');
require('../scss/app.scss')

/*
 * Icons
 */
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

require('bootstrap');
$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});

/**
 * Abrir link
 * @param {string} url
 * @param {boolean} target
 */
$(document).on('click','.abrir', function(e){
    // e.preventDefault();
    var url=$(this).parent().data("url");
    var target=$(this).parent().data("target");
    if(target){
        return window.open(url, '_blank');
    }else{
        return window.location = url;
    }
});


/**
 * Enviar cambios en linea
 */
// $('input.input-send').on('change',function(e){

    // $.ajax({
    //   url: 'your url',
    //   global: false,
    //   type: 'GET',
    //   data: {},
    //   async: false, //blocks window close
    //   success: function(msj) {
    //     alert('onchange');
    //   }
  // e.preventDefault();
  // var ref=$(this).attr('href');
  // if($('div#ajaxContainer-'+ref).hasClass('HI')){
  //      $('div#ajaxContainer-'+ref).removeClass('HI');
  // }else{
  //      $('div.ver-infoL2').toggle(300)//addClass('HI');
  // }
// });


$(function() {
    $("ul.dropdown-menu [data-toggle='dropdown']").on("click", function(event) {
        event.preventDefault();
        event.stopPropagation();
        $(this).parents('.dropdown-submenu').siblings().find('.show').removeClass("show");
        $(this).siblings().toggleClass("show");
        $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
            $('.dropdown-submenu .show').removeClass("show");
        });
    });
});


// console.log('Encore: assets/js/app.js');