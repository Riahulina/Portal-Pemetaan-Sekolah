@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ $message->embed(public_path('assets/logo.png')) }}" class="logo" alt="SatuPeta Logo" style="max-height: 50px; width: auto;">
</a>
</td>
</tr>
