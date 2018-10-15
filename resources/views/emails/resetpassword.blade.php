<div>
    <h3>Hi {{ $users->username }},</h3> 
    <p>Welcome to QU. You can change the password click on below link.</p>
    <a href="{{ asset('api/resetPassword/'.$userID) }}">Reset Password Link.</a>
    <p> Thank you! QU Team </p>
</div>    
   