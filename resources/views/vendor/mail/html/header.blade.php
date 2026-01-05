@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')

<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRo05uIrLJY4z6xFFct8PZB4a6qVVbOdD7-uA&s" class="logo" alt="Laravel Logo">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
