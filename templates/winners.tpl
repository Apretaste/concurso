<h1>Ganadores</h1>
{space5}
<table cellspacing="0" cellpadding="10" border="0" width="100%">
		<tr>
			<th>Concurso</th>
			<th><font color="#FFD700" size="8">&#10102;</font><br/></th>
			<th><font color="#A9A9A9" size="8">&#10103;</font><br/></th>
			<th><font color="#cd7f32" size="8">&#10104;</font><br/></th>
		</tr>
	{foreach item=item from=$contests}
		<tr {if $item@iteration is odd}style="background-color:#F2F2F2;"{/if}>
			<td>{link href="CONCURSO VER {$item->id}" caption="{$item->title}"}<br/>
				<i>{$item->end_date|date_format:"%d/%m/%Y"}</i>
			</td>
			<td align ="center">
				{if $item->winner1 !== false}
					{link href="PERFIL @{$item->winner1->username}" caption="@{$item->winner1->username}"}
				{/if}
			</td>
			<td align ="center">
				{if $item->winner2 !== false}
					{link href="PERFIL @{$item->winner2->username}" caption="@{$item->winner2->username}"}
				{/if}
			</td>
			<td align ="center">
				{if $item->winner3 !== false}
					{link href="PERFIL @{$item->winner3->username}" caption="@{$item->winner3->username}"}
				{/if}
			</td>
		</tr>
	{/foreach}
</table>
{space5}
<center>{button href="CONCURSO" caption="Concursos"}</center>
