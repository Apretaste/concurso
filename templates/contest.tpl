<h1>{$contest->title}</h1>
<p>
    {if $contest->is_open === '0'}
        Cierra el {$contest->end_date|date_format:"%d de %B del %Y"}<br/>
        {if $contest->prize1 !== '' || $contest->prize2 !== '' || $contest->prize3 !== ''}
            {if $contest->prize1 !== '' && $contest->prize2 == '' && $contest->prize3 == ''}
                <p>Premio: {$contest->prize1}</p>
            {else}
                <p>Premios:
                {if $contest->prize1 !== ''}
                    <font color="#FFD700" size="4">&#10102;</font>{$contest->prize1}
                {/if}
                {if $contest->prize2 !== ''}
                    <font color="#A9A9A9" size="4">&#10103;</font>{$contest->prize2}
                {/if}
                {if $contest->prize3 !== ''}
                    <font color="#cd7f32" size="4">&#10104;</font>{$contest->prize3}
                {/if}
                </p>
            {/if}
        {/if}
    {else}
        <font color="red">Este concurso cerr&oacute; el {$contest->end_date|date_format:"%d de %B del %Y"}</i></font><br/>
        {if $contest->winner1 !== '' || $contest->winner2 !== '' || $contest->winner3 !== ''}
            {if $contest->winner1 !== '' && $contest->winner2 == '' && $contest->winner3 == ''}
                <p>Ganador: {link href="PERFIL {$contest->winner1->username}" caption="@{$contest->winner1->username}"}</p>
            {else}
            <p>Ganadores:
                {if $contest->winner1 !== false}
                <font color="#FFD700" size="4">&#10102;</font>{link href="PERFIL {$contest->winner1->username}" caption="@{$contest->winner1->username}"}
                {/if}
                {if $contest->winner2 !== false}
                <font color="#A9A9A9" size="4">&#10103;</font>{link href="PERFIL {$contest->winner2->username}" caption="@{$contest->winner2->username}"}
                {/if}
                {if $contest->winner3 !== false}
                <font color="#cd7f32" size="4">&#10104;</font>{link href="PERFIL {$contest->winner3->username}" caption="@{$contest->winner3->username}"}
                {/if}
            </p>
            {/if}
        {/if}
    {/if}
    <br/>
    {$contest->body}
    <br/>
</p>

{space5}
<center>{button href="CONCURSO" caption="Concursos"}</center>