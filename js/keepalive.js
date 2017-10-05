$(document).ready(function () {

//JS to update time_locked and locked_by every X minutes
setInterval(function() {

                //console.log('HERE');
                var qid =  $('#question_id').val();
                var uid =  $('#user_id').val();
                var ajaxkeepaliveurl = $('#ajaxkeepaliveurl').val();
             $.ajax({
                type: 'POST',
                url: ajaxkeepaliveurl,
                data: {user_id: uid, question_id: qid},
                success: function(response) {
                    
                    //console.log('success');
               
                    //console.log(response);
                }
            });


  //your jQuery ajax code
}, 60*1000*2);







});
