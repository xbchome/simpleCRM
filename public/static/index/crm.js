function tableS(value) {
    var id = $("select[name=quiz]").val();
    var txt = $("select[name=quiz]").find('option:selected').attr('data-txt');
    if(txt==undefined)
        return false;
    $("select[name=quiz]").find('option:selected').attr('disabled','true');
//      var txt = $("select[name=quiz]").find('option:selected').attr('data-id');
    var html ='<span class="Label-data" id="del-'+id+'">\n' + txt+''+
        '    <input type="hidden" name="fananser['+id+']" value="'+txt+'"/> \n' +
        '<a href="javascript:void (0);" data-id="'+id+'" onclick="delLabel(this)">X</a>' +
        '    </span>';
    $("#my-inline").append(html);

}
function delLabel(obj) {
    var id = $(obj).attr('data-id');
    $('#del-'+id).remove();
    $('#d-'+id).removeAttr('disabled');
}