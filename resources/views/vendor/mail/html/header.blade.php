@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<span style="display: inline-flex; align-items: center; gap: 8px;">
    <span style="display: inline-block; width: 32px; height: 32px; background: linear-gradient(135deg, #6366f1, #9333ea); border-radius: 8px; text-align: center; line-height: 32px; color: #fff; font-weight: bold; font-size: 16px;">B</span>
    <span style="font-size: 22px; font-weight: bold; color: #1f2937;">{{ $slot }}</span>
</span>
</a>
</td>
</tr>
