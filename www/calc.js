/**
 * Created by X-iLeR on 30.07.2016.
 */
$(document).ready(function() {
    $('.watcalc-input').click(function(e){
        var $this = $(this);
        $this.parent().find('.active').removeClass('active');
        $this.addClass('active');
    });
    $('.watcalc-inputs .reset').click(function(e){
        $('.watcalc-input').removeClass('active');
    });

    $('.watcalc-cell.btn a').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        var data = {};
        $('.watcalc-input.active').each(function(i,el){
            var $input = $(el);
            var val = $input.text().trim();
            var col = $input.data('col');
            data[col] = val;
        });
        $.ajax({
            url : '/process_calc.php',
            data : {criteria : data},
            type : 'post',
            error : function(err) {
                console.dir(err);
            },
            success : function(data) {
                var scheme = {};
                var result = {};
                try {
                    result = JSON.parse(data);
                    if(result.status == 'OK') {
                        scheme = result.scheme;
                        $('#scheme').html(scheme);
                    } else {
                        console.log('Response error');
                        console.log(result.msg);
                    }
                } catch(e) {
                    console.log('Malformed response');
                    console.dir(e);
                }
            }
        })

    });

});
