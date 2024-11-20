# DES加解密工具类

> 来源：https://blog.csdn.net/veloi/article/details/125572745

## 简介

跨语言做 DES 加密解密经常会出现问题，往往是填充方式不对、编码不一致或者加密解密模式没有对应上造成。
- 填充模式：pkcs5、pkcs7、iso10126、ansix923、zero。
- 加密模式：DES-ECB、DES-CBC、DES-CTR、DES-OFB、DES-CFB。
- 输出类型：无编码，base64编码，hex编码。

本工具使用OpenSSL方式实现的DES加解密，

## 使用

```php
$key = 'key123456';
$iv = 'iv123456';
 
// DES CBC 加解密
$des = new DES($key, 'DES-CBC', DES::OUTPUT_BASE64, $iv);
echo $base64Sign = $des->encrypt('Hello DES CBC');
echo "\n";
echo $des->decrypt($base64Sign);
echo "\n";
 
// DES ECB 加解密
$des = new DES($key, 'DES-ECB', DES::OUTPUT_HEX);
echo $base64Sign = $des->encrypt('Hello DES ECB');
echo "\n";
echo $des->decrypt($base64Sign);
```