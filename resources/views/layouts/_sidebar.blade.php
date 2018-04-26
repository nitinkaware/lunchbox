<div class="list-group">
    <a href="{{ route('dashboard.index') }}"
       class="list-group-item list-group-item-action @if(request()->routeIs('dashboard.index')) active @endif">Dashboard</a>
    <a href="{{ route('payments.index') }}"
       class="list-group-item list-group-item-action @if(request()->routeIs('payments.index')) active @endif">Payments</a>
    <a href="{{ route('orders.index') }}"
       class="list-group-item list-group-item-action @if(request()->routeIs('orders.index')) active @endif">Orders</a>
    <a href="{{ route('meals.summery.index') }}"
       class="list-group-item list-group-item-action @if(request()->routeIs('meals.summery.index')) active @endif">Meals
        Summery</a>
</div>