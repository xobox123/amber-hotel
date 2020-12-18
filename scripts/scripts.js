function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [month, day, year].join('/');


}
    //var today = moment().format('MM-DD-YYYY');
    //var tomorrow = today.addDays(1);
    //var today = new window.Date.today().addDays(1).toString("dd-mm-yyyy");
    var today = new Date();
    var tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);

$(document).ready(function(){

    var newLink = "rooms.php?startDate="+formatDate(today)+"&endDate="+formatDate(tomorrow);
    $("#reservation-link").attr("href", newLink); // Set herf value

});

    //alert(formatDate(today));
    //alert(formatDate(tomorrow));
    $(function() {
    $('input[name="daterange"]').daterangepicker({
        opens: 'left',
        minDate: today
    }, function(start, end, label) {
        $startDate = start.format('MM/DD/YYYY');
        $endDate = end.format('MM/DD/YYYY');
        console.log(start.format('MM-DD-YYYY') + ' to ' + end.format('MM-DD-YYYY'));
        //$.get( "rooms.php", { startDate: start.format('MM-DD-YYYY'), endDate: end.format('MM-DD-YYYY')} );

        window.location = "rooms.php?startDate=" + $startDate+"&endDate="+$endDate;
    });
    });

    
