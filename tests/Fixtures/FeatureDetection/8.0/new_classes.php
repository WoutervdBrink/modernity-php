#Test
Description: New classes in PHP 8.0
Parser: 8.0
Min: 8.0
EveryLine: true

<?php
$wm = new WeakMap();
try { } catch (ValueError $e) {}
class Foo implements Stringable {}
echo CurlHandle::class;
echo CurlMultiHandle::class;
echo CurlShareHandle::class;
echo EnchantBroker::class;
echo EnchantDictionary::class;
echo GdImage::class;
echo OpenSSLAsymmetricKey::class;
echo OpenSSLCertificate::class;
echo OpenSSLCertificateSigningRequest::class;
echo Shmop::class;
echo AddressInfo::class;
echo Socket::class;
echo SysvMessageQueue::class;
echo SysvSemaphore::class;
echo SysvSharedMemory::class;
echo XMLParser::class;
echo XMLWriter::class;
echo DeflateContext::class;
echo InflateContext::class;
?>