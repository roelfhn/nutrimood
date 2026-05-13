<div id="layoutSidenav_nav">
  <nav class="sidenav">
    <div class="sidenav-menu">
      <div class="sidenav-section-title">Core</div>

      <a class="sidenav-link" href="welcome.php">
        <i class="fas fa-tachometer-alt sidenav-icon"></i>
        <span>Dashboard</span>
      </a>

      <a class="sidenav-link" href="profile.php">
        <i class="fas fa-user sidenav-icon"></i>
        <span>Profile</span>
      </a>

      <a class="sidenav-link" href="change-password.php">
        <i class="fas fa-key sidenav-icon"></i>
        <span>Change Password</span>
      </a>

      <a class="sidenav-link" href="logout.php">
        <i class="fas fa-sign-out-alt sidenav-icon"></i>
        <span>Signout</span>
      </a>
    </div>
  </nav>
</div>

<style>
  .sidenav {
    width: 220px;
    background-color: #1e293b;
    color: #fff;
    height: 100vh;
    padding: 1rem 0;
    overflow-y: auto;
  }

  .sidenav-menu {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    padding: 0 1rem;
  }

  .sidenav-section-title {
    font-size: 0.75rem;
    font-weight: bold;
    text-transform: uppercase;
    color: #94a3b8;
    margin-bottom: 1rem;
    padding-left: 0.25rem;
  }

  .sidenav-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #e2e8f0;
    text-decoration: none;
    padding: 0.6rem 0.75rem;
    border-radius: 0.375rem;
    transition: background 0.2s ease;
  }

  .sidenav-link:hover {
    background-color: #334155;
    color: #fff;
  }

  .sidenav-icon {
    width: 20px;
    text-align: center;
  }

  /* Optional: Highlight active link */
  .sidenav-link.active {
    background-color: #475569;
  }
</style>
