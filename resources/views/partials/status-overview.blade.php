<dl class="dl-horizontal">
    <dt>queued</dt>
    <dd>The email was created in our system and send to Mailgun for processing.</dd>
    <dt>delivered</dt>
    <dd>A successful delivery occurs when the recipient email server responds that it has accepted the message.</dd>
    <dt>dropped</dt>
    <dd>There are several reasons why Mailgun stops attempting to deliver messages and drops them including: hard
        bounces, messages that reached their retry limit, previously unsubscribed/bounced/complained addresses, or
        addresses rejected by an ESP.
    </dd>
    <dt>bounced</dt>
    <dd>When the recipient email server specifies that the recipient address does not exist.</dd>
    <dt>spam_complaints</dt>
    <dd>When a user reports one of your emails as spam. Note that not all ESPs provide this feedback.</dd>
    <dt>unsubscribe</dt>
    <dd>When a user unsubscribes, either from all messages, a specific tag or a mailing list.</dd>
    <dt>clicked</dt>
    <dd>Every time a user clicks on a link in your messages.</dd>
    <dt>opened</dt>
    <dd>Every time a user opens one of your messages.</dd>
</dl>