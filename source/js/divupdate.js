var cacheData;
var data = $('#liveroom').html();
var auto_refresh = setInterval(
function ()
{
    $.ajax({
        url: 'side/openrooms.php',
        type: 'POST',
        data: data,
        dataType: 'html',
        success: function(data) {
            if (data !== cacheData){
                //data has changed (or it's the first call), save new cache data and update div
                cacheData = data;
                $('#liveroom').html(data);
            }          
        }
    })
}, 300); // check every 300 milliseconds
