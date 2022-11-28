{* smarty *}
{include file='header.tpl' title=$title description=$description}
<h2>کارت(های) خریداری شده</h2>
<div class="cards">
<table width="100%">
<tr>
<td width="25%" class="top">نوع</td>
{if $product.product_first_field_title}
	<td width="25%" class="top">{$product.product_first_field_title}</td>
{/if}
{if $product.product_second_field_title}
	<td width="25%" class="top">{$product.product_second_field_title}</td>
{/if}
{if $product.product_third_field_title}
	<td width="25%" class="top">{$product.product_third_field_title}</td>
{/if}
</tr>
{foreach $cards as $card}
<tr>
	<td>{$product.product_title}</td>
	{if $product.product_first_field_title}
		<td align="center">{$card.card_first_field|nl2br}</td>
	{/if}
	{if $product.product_second_field_title}
		<td align="center">{$card.card_second_field|nl2br}</td>
	{/if}
	{if $product.product_third_field_title}
		<td align="center">{$card.card_third_field|nl2br}</td>
	{/if}
</tr>
{/foreach}
</table>
<div class="box">
{$product.product_body|nl2br}
</div>
</div>
     <div class="cleaner"></div>
{include file='footer.tpl'}