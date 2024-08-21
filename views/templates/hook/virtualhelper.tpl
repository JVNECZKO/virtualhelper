{if $caretaker}
<div class="product-caretaker-box">
    <h3>{l s='Product Caretaker' mod='virtualhelper'}</h3>
    <div class="caretaker-avatar">
        <img src="{$caretaker.avatar}" alt="{$caretaker.name}">
    </div>
    <div class="caretaker-info">
        <p><strong>{l s='Name:' mod='virtualhelper'}</strong> {$caretaker.name}</p>
        <p><strong>{l s='Contact Number:' mod='virtualhelper'}</strong> {$caretaker.contact_number}</p>
        <p><strong>{l s='Email:' mod='virtualhelper'}</strong> <a href="mailto:{$caretaker.email}">{$caretaker.email}</a></p>
    </div>
</div>
{/if}
