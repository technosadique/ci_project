<div class="main-content" style="margin-left:810px;">
        <h4>Welcome, <span id="username"><?php echo ucwords($_SESSION['username']); ?> | <a href="<?php echo BASE_URL  ?>home/logout" onclick="return confirm('Are you sure, you want to logout?')">Logout</a></span>!</h4>      
        <!-- Additional content can go here -->
</div>