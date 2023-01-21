<table align="center" width="100%" border="0" class="animate__animated animate__fadeInDown"
    style="background-color: {{ $backgroundColor }};margin-bottom: 10px;border-radius: 5px;color: {{ $textColor }};">
    <tr style="font-size:18pt;">
        <td style="text-align:center;">{{ $faculty }}</td>
    </tr>
    <tr style="font-size:18pt;">
        <td style="text-align:center;">{{ $institute }}</td>
    </tr>
    <tr style="font-size:18pt;">
        <td style="text-align:center;">{{ $program . ' PROGRAM' }}</td>
    </tr>
    <tr style="font-size:18pt;">
        <td style="text-align:center;">{{ Str::upper($session) }}</td>
    </tr>
</table>
