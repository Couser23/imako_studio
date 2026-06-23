@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
    <img src="{{ asset('images/logo.PNG') }}" class="logo" alt="{{ config('app.name') }} Logo" style="max-height: 50px; width: auto;">
</a>
</td>
</tr>
