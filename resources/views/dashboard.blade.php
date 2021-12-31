Admin Dashboard


<form method="POST" action="{{ route('admin.logout') }}">
      @csrf
    <button type="submit">Logout</button>
</form>