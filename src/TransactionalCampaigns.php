<?php

namespace mataluis2k\marapost;

use mataluis2k\marapost\Abstractions\Api;
use mataluis2k\marapost\ResultTypes\GetResult;
use mataluis2k\marapost\Abstractions\OperationResult;

/**
 * Class TransactionalCampaigns
 * @package mataluis2k\marapost
 */
class TransactionalCampaigns
{
    use Api;

	public function __construct(int $accountId, string $authToken)
	{
		$this->auth_token = $authToken;
		$this->accountId = $accountId;
        $this->resource = 'transactional_campaigns';
	}

    /**
     * Gets the list of Transaction Campaigns.
     *
     * @param int $page page #. (>= 1)
     * @return GetResult
     */
	public function get(int $page) : GetResult
	{
        try {
            return $this->_get('', ['page' => $page]);
        } catch (\Exception $e) {
            die('exception ');
        }
	}

    /**
     * Creates a Transactional Campaign
     *
     * @param string $name campaign name
     * @param string $subject campaign subject
     * @param string $preheader campaign preheader
     * @param string $fromName sender name in the email
     * @param string $fromEmail sender email address
     * @param string $replyTo reply-to email address
     * @param int $contentId
     * @param bool $emailPreviewLink
     * @param string $address
     * @param string $language ISO 639-1 language code
     * @param string ...$ctags array of campaign tags
     * @return OperationResult
     */
	public function create(
        string $name,
        string $subject,
        string $preheader,
        string $fromName,
        string $fromEmail,
        string $replyTo,
        int $contentId,
        bool $emailPreviewLink,
        string $address,
        string $language,
        string... $ctags
    ) : OperationResult
	{
	    $campaign = (object)[
	      "name" => $name,
          "subject" => $subject,
          "preheader" => $preheader,
          "from_name" => $fromName,
          "from_email" => $fromEmail,
          "reply_to" => $replyTo,
          "content_id" => strval($contentId),
          "email_preview_link" => $emailPreviewLink,
          "address" => $address,
          "language" => $language
        ];
	    if (count($ctags) > 0) {
	        $campaign->add_ctags = $ctags;
        }

	    $object = (object)["campaign" => $campaign];
	    $result = $this->_post("", [], $object);
		return $result;
	}

    /**
     * Sends a transactional campaign email to a recipient. Sender's information will be automatically fetched from the
     * transactional campaign, unless provided in the function arguments.
     *
     * @param int $campaignId must be a campaign that already exists when you call ->get(). If you don't have one, first call ->create().
     * @param int|null $contentId If provided, the transactional campaign's content will be replaced by this content.
     * @param string|null $contentName If $contentId is null, the transactional campaign's content name will be replaced by this name.
     * @param string|null $contentHtmlPart If $contentId is null, the transactional campaign's content HTML part will be replaced by this HTML part.
     * @param string|null $contentTextPart If $contentId is null, the transactional campaign's content Text part will be replaced by this Text part.
     * @param int|null $sendAtHour Must be 1-12. Otherwise the email will go out immediately. If the hour is less than the current hour, the email will go out the following day.
     * @param int|null $sendAtMinute Must be 0-60. Otherwise will be treated as 0. If the hour and minute combine to less than the current time, the email will go out the following day.
     * @param bool $ignoreDnm If true, ignores the Do Not Mail list for the recipient contact.
     * @param int|null $contactId contact ID of the recipient.
     * @param string|null $recipientEmail email address. Ignored unless $contactId is null. Otherwise, it must be a well-formed email address according to FILTER_VALIDATE_EMAIL.
     * @param string|null $recipientFirstName recipient's first name. Ignored unless $contactId is null.
     * @param string|null $recipientLastName recipient's last name. Ignored unless $contactId is null.
     * @param array|null $recipientCustomFields custom fields for the recipient. Ignored unless $contactId is null. Is an associative array where the item key is the name of the custom field, and the item value is the field value. All keys must be strings. All values must be non-null scalars.
     * @param string|null $bccEmail BCC recipient. May only pass a single email address, empty string, or null. If provided, it must be a well-formed email address according to FILTER_VALIDATE_EMAIL.
     * @param string|null $fromName sender's name. If $fromEmail is set, it overrides the transactional campaign default sender name. Ignored otherwise.
     * @param string|null $fromEmail sender's email address. Overrides the transactional campaign default sender email.
     * @param string|null $subject subject line of email. Overrides the transactional campaign default subject.
     * @param string|null $replyTo reply-to address. Overrides the transactional campaign default reply-to.
     * @param string|null $senderAddress physical address of sender. Overrides the transactional campaign default sender address.
     * @param array|null $tags associative array where the item key is the name of the tag within the content, and the item value is the tag's replacement upon sending. All keys must be strings. All values must be non-null scalars.
     * @param array|null $ctags campaign tags. Must be a simple array of scalar values.
     * @return OperationResult data property contains information about the newly created campaign.
     */
	public function sendEmail(
	    int $campaignId,
        int $contentId = null,
        string $contentName = null,
        string $contentHtmlPart = null,
        string $contentTextPart = null,
        int $sendAtHour = null,
        int $sendAtMinute = null,
        bool $ignoreDnm = null,
        int $contactId = null,
        string $recipientEmail = null,
        string $recipientFirstName = null,
        string $recipientLastName = null,
        array $recipientCustomFields = null,
        string $bccEmail = null,
        string $fromName = null,
        string $fromEmail = null,
        string $subject = null,
        string $replyTo = null,
        string $senderAddress = null,
        array $tags = null,
        array $ctags = null
    ) : OperationResult
	{
	    $emailObj = new \stdClass();
        $emailObj->campaign_id = $campaignId;
        $contentFlag = 0; // nothing provided
        if (!is_null($contentId)) {
            $emailObj->content_id = $contentId;
            $contentFlag = 1; // contentId provided
        }
        if ($contentHtmlPart || $contentTextPart || $contentName) {
            $emailObj->content = (object)array(
                "name" => $contentName,
                "html_part" => $contentHtmlPart,
                "text_part" => $contentTextPart
            );
            $contentFlag = $contentFlag | 2; // content field(s) provided
        }
        if ($contentFlag == 3) {
            return new GetResult(null, "You may provide EITHER a contentId OR content field values, but not both.");
        }
	    if (is_null($contactId)) {
            if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
                return new GetResult(null, "You must provide a well-formed recipientEmail because contactId is null.");
            }
            $emailObj->contact = (object)array(
                "email" => $recipientEmail,
                "first_name" => $recipientFirstName,
                "last_name" => $recipientLastName
            );
            if (!is_null($recipientCustomFields)) {
                foreach ($recipientCustomFields as $key => $value) {
                    if (!is_string($key)) {
                        return new GetResult(null, "All keys in your recipientCustomFields array must be strings.");
                    }
                    if (!is_scalar($value)) {
                        return new GetResult(null, "All values in your recipientCustomFields array must be non-null scalars (string, float, bool, int).");
                    }
                }
                $emailObj->custom_field = (object)$recipientCustomFields;
            }
        }
        else {
            $emailObj->contact_id = $contactId;
        }
	    if ($sendAtHour > 0 && $sendAtHour <= 12) {
	        if (!($sendAtMinute >= 0 && $sendAtMinute <= 60)) {
	            $sendAtMinute = 0;
            }
            $emailObj->send_time = (object)array(
                "hour" => strval($sendAtHour),
                "minute" => strval($sendAtMinute)
            );
        }
	    if ($ignoreDnm) {
	        $emailObj->ignore_dnm = true;
        }
	    if (strlen($fromEmail) != 0) {
	        $emailObj->from_email = $fromEmail;
	        $emailObj->from_name = $fromName;
        }
	    if (strlen($replyTo) != 0) {
	        $emailObj->reply_to = $replyTo;
        }
	    if (strlen($subject) != 0) {
            $emailObj->subject = $subject;
        }
	    if (strlen($senderAddress) != 0) {
            $emailObj->address = $senderAddress;
        }
	    if (strlen($bccEmail) != 0) {
	        if (filter_var($bccEmail, FILTER_VALIDATE_EMAIL)) {
                $emailObj->bcc = $bccEmail;
            }
	        else {
	            return new GetResult(null, "When providing a bccEmail, it needs to be a well-formed email address.");
            }
        }
	    if (!empty($tags)) {
	        foreach ($tags as $key => $value) {
	            if (!is_string($key)) {
                    return new GetResult(null, "All keys in your tags array must be strings.");
                }
	            if (!is_scalar($value)) {
	                return new GetResult(null, "All values in your tags array must be non-null scalars (string, float, bool, int).");
                }
            }
	        $emailObj->tags = (object)$tags;
        }
	    if (!empty($ctags)) {
            foreach ($ctags as $value) {
                if (!is_scalar($value)) {
                    return new GetResult(null, "All values in your ctags array must be non-null scalars (string, float, bool, int).");
                }
            }
	        $emailObj->add_ctags = $ctags;
        }
	    $object = new \stdClass();
	    $object->email = $emailObj;
		$result = $this->_post("deliver", [], $object, "emails");
		return $result;
	}
}