<?php
namespace phputil;

use \phputil\HttpStatus;
use \phputil\HttpStatusText;
use \phputil\HttpResponseHeader;
use \phputil\Mime;
use \phputil\Charset;
use \phputil\Encoding;
use \phputil\JSON;

use \Psr\Http\Message\ResponseInterface;

/**
 * Wrapper for a PSR-7 response interface.
 *
 * @author	Thiago Delgado Pinto 
 */
class HttpResponseWrapper {
	
	private $response;
	
	function __construct() {}
	
	// HELPER METHODS
	
	/**
	 * Return a BAD REQUEST (400) with the content (optional) as JSON UTF-8.
	 *
	 * @return ResponseInterface
	 */
	function bad( $content = null ) {
		if ( isset( $content ) ) {
			return $this->withStatusBadRequest()->asJsonUtf8( $content )->end();		
		}
		return $this->withStatusBadRequest()->end();		
	}
	
	/**
	 * Return an OK (200) with the content (optional) as JSON UTF-8.
	 *
	 * @return ResponseInterface
	 */	
	function ok( $content = null ) {
		if ( isset( $content ) ) {
			return $this->withStatusOk()->asJsonUtf8( $content )->end();
		}
		return $this->withStatusOk()->end();
	}
	
	/**
	 * Return a CREATED (201) with the content (optional) as JSON UTF-8.
	 *
	 * @return ResponseInterface
	 */	
	function created( $content = null ) {
		if ( isset( $content ) ) {
			return $this->withStatusCreated()->asJsonUtf8( $content )->end();
		}
		return $this->withStatusCreated()->end();
	}	
	
	/**
	 * Return a NO CONTENT (204).
	 *
	 * @return ResponseInterface
	 */		
	function noContent() {
		return $this->withStatusNoContent()->end();
	}	
	
	// GETTERS & SETTERS

	function get() {
		return $this->response;
	}
	
	function response() {
		return $this->response;
	}
	
	function then() {
		return $this->response;
	}
	
	function end() {
		return $this->response;
	}
	
	function set( ResponseInterface $response ) {
		$this->response = $response;
		return $this;
	}
	
	function with( ResponseInterface $response ) {
		return $this->set( $response );
	}
	
	// STATUS
	
	function withStatusOk() {
		return $this->set( $this->response->withStatus(
			HttpStatus::OK, HttpStatusText::OK ) );
	}
	
	function withStatusCreated() {
		return $this->set( $this->response->withStatus(
			HttpStatus::CREATED, HttpStatusText::CREATED ) );
	}
	
	function withStatusNoContent() {
		return $this->set( $this->response->withStatus(
			HttpStatus::NO_CONTENT, HttpStatusText::NO_CONTENT ) );
	}
	
	function withStatusBadRequest() {
		return $this->set( $this->response->withStatus(
			HttpStatus::BAD_REQUEST, HttpStatusText::BAD_REQUEST ) );
	}
	
	function withStatusUnauthorized() {
		return $this->set( $this->response->withStatus(
			HttpStatus::UNAUTHORIZED, HttpStatusText::UNAUTHORIZED ) );
	}
	
	function withStatusForbidden() {
		return $this->set( $this->response->withStatus(
			HttpStatus::FORBIDDEN, HttpStatusText::FORBIDDEN ) );
	}	
	
	// HEADER
	
	function withHeaderTextUtf8() {
		return $this->withHeaderContentType( Mime::TEXT, Charset::UTF_8 );
	}	
	
	function withHeaderHtmlUtf8() {
		return $this->withHeaderContentType( Mime::HTML, Charset::UTF_8 );
	}	
	
	function withHeaderJsonUtf8() {
		return $this->withHeaderContentType( Mime::JSON, Charset::UTF_8 );
	}
	
	function withHeaderGziped() {
		return $this->withHeaderContentEncoding( Encoding::GZIP );
	}	
	
	function withHeaderContentType( $mime = 'text/html', $charset = 'utf-8' ) {
		return $this->set( $this->response->withAddedHeader(
			HttpResponseHeader::CONTENT_TYPE,
			Mime::makeWithCharset( $mime, $charset )
			) );
	}
	
	function withHeaderContentEncoding( $encoding = 'gzip' ) {
		return $this->set( $this->response->withAddedHeader(
			HttpResponseHeader::CONTENT_ENCODING, $encoding ) );
	}
	
	// "without" (mostly used for security reasons)
	
	function withoutServer() {
		return $this->set( $this->response->withoutHeader(
			HttpResponseHeader::SERVER ) );
	}
	
	function withoutXPoweredBy() {
		return $this->set( $this->response->withoutHeader(
			HttpResponseHeader::X_POWERED_BY ) );
	}	
	
	// BODY
	
	function withBody( $body ) {
		return $this->set( $this->response->write( $body ) );
	}
	
	function withBodyGziped( $body, $compressionLevel = 6 ) {
		$compressedBody = gzencode( $body, $compressionLevel );
		return $this->set( $this->response->write( $compressedBody ) );
	}
	
	// COMMON
	
	// JSON + UTF-8 (header and body), converts the given content.
	function asJsonUtf8( $var ) {
		return $this->set( $this->withHeaderJsonUtf8()->get()->write(
			$this->toJson( $var ) ) );
	}

	// GZIP (header and body), compresses the given content.
	function asGziped( $body, $compressionLevel = 6 ) {
		return $this->withHeaderGziped()
			->withBodyGziped( $body, $compressionLevel );
	}
	
	// JSON + UTF-8 + GZIP (header and body), converts and compresses
	// the given content.
	function asGzipedJsonUtf8( $var, $compressionLevel = 6 ) {
		return $this
			->withHeaderJsonUtf8()
			->withHeaderGziped()
			->withBodyGziped( $this->toJson( $var ), $compressionLevel )
			;
	}
	
	private function toJson( $var ) {
		return JSON::encode( $var, 'get', true );
	}
	
}

?>