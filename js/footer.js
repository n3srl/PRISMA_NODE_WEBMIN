/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function(){
    getCurrentUser();
})
function getCurrentUser(){
    let current_name_person = new PersonModel('v1');
    var f = function(){
        $(".currentUsername").html(stripHtml(current_name_person.username));
    }
    getAjax(current_name_person, '/lib/core/v1/getcurrentuser', f);
}