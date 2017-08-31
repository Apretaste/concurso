<h1>Concursos</h1>

<table width="100%">
{foreach item=item from=$contests}
    <tr>
    <td>
        {link href="CONCURSO VER {$item->id}" caption="{$item->title}"}<br/>
        Cierra el {$item->end_date|date_format:"%d de %B del %Y"}<br/>
        {if $item->prize1 !== '' || $item->prize2 !== '' || $item->prize3 !== ''}
            {if $item->prize1 !== '' && $item->prize2 == '' && $item->prize3 == ''}
                Premio: {$item->prize1}
            {else}
                Premios:
                {if $item->prize1 !== ''}
                    <font color="#FFD700" size="4">&#10102;</font>{$item->prize1}
                {/if}
                {if $item->prize2 !== ''}
                    <font color="#A9A9A9" size="4">&#10103;</font>{$item->prize2}
                {/if}
                {if $item->prize3 !== ''}
                    <font color="#cd7f32" size="4">&#10104;</font>{$item->prize3}
                {/if}
            {/if}
        {/if}
        {space5}
    </td>
    <td align="center">
        {button size="small" href="CONCURSO VER {$item->id}" caption="Ver"}
    </td>
    </tr>
{/foreach}
</table>
{space5}
<center>{button href="CONCURSO GANADORES" caption="Ganadores"}</center>