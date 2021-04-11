<?php


namespace SL;


class ShortLinkSQLite
{
    private \PDO $pdo;

    public static function getInstanceByID(int $id)
    {
        $stmt = static::getPdo()->prepare(
            "select * from links where id=?"
        );
        $result = $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (empty($row)) {
            return null;
        }
        if ($result && !empty($row)) {
            return $row;
        }
    }

    private static function getPdo(): \PDO

    {
        $dns = "sqlite:".Config::PATH_TO_SQLITE_FILE;
        $pdo = new \PDO($dns, null, null);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public static function getUrlByShortLink(string $shortLink): ?string
    {
        $stmt = static::getPdo()->prepare(
            "select id, (
                                    select urls.url from urls where urls.id=id
                                ) as url  
                    from links where shortLink=?"
        );
        $result = $stmt->execute([$shortLink]);
        $row = $stmt->fetch();
        if (empty($row)) {
            return null;
        }
        if ($result) {
            return $row['url'];
        }
    }

    public static function getShortLinksByUrl(string $url): ?array
    {
        $stmt = static::getPdo()->prepare(
            "select id, 
                           (
                               select urls.url from urls where urls.id = id 
                           ) as url ,
                           shortLink
                    from links where url=?"
        );
        $result = $stmt->execute([$url]);

        $shortLinks = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $shortLinks[] = new Link(
                id: $row['id'],
                url: $row['url'],
                shortUrl: $row['shortLink']
            );
        }
        return $shortLinks;
    }

    public static function addLink(string $url): Link
    {

        $url = trim($url);
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return new Link(id:null,url: 'Not Valid', shortUrl: 'Not Valid Url');
        }

        $shortLink = static::generateShortLink($url);

        if (!$url_id = static::getIdUrl($url)) {
            $query = "insert into urls(url) values (:url)";
            $stmt = static::getPdo()->prepare($query);
            $stmt->execute([':url' => $url]);
            $url_id = static::getIdUrl($url);
        }

        $query = "insert into links(url_id, shortLink) values (:url_id, :shortLink)";
        $stmt = static::getPdo()->prepare($query);
        $stmt->bindValue(':url_id', $url_id);
        $stmt->bindValue(':shortLink', $shortLink);
        $stmt->execute();

        return static::getLink($shortLink);
    }

    public static function generateShortLink($url): string
    {
        $url = str_replace(parse_url($url, PHP_URL_SCHEME).'://', '', $url);
        $converted = str_replace('@', '', $url);
        $converted = str_replace('/', '', $converted);
        $converted = str_replace('.', '', $converted);
        return static::randomString($converted);
    }

    public static function randomString(string $chars='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', int $length = 10): string
    {
        $randomStr = '';

        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $randomStr .= $chars[rand(0, $size - 1)];
        }
        if (static::isUnique($randomStr)) {
            return $randomStr;
        }else {
            static::randomString();
        }

        return $randomStr . '---';
    }

    public static function isUnique(string $srt): bool
    {
        if (static::getIdByShortLink($srt)) {
            return false;
        }
        return true;
    }

    public static function getIdUrl(string $url)
    {
        $stmt = static::getPdo()->prepare(
            "select id from urls where url=?"
        );
        $result = $stmt->execute([$url]);
        $row = $stmt->fetch();
        if (empty($row)) {
            return null;
        }
        if ($result) {
            return $row['id'];
        }
    }

    public static function getIdByShortLink(string $shortLink)
    {
        $stmt = static::getPdo()->prepare(
            "select id from links where shortLink=?"
        );
        $result = $stmt->execute([$shortLink]);
        $row = $stmt->fetch();
        if (empty($row)) {
            return null;
        }
        if ($result) {
            return $row['id'];
        }
    }

    public static function getIdByUrl(string $url)
    {
        $stmt = static::getPdo()->prepare(
            "select id from urls where url=?"
        );
        $result = $stmt->execute([$url]);
        $row = $stmt->fetch();
        if (empty($row)) {
            return null;
        }
        if ($result) {
            return $row['id'];
        }
    }

    public static function getLink(string $shortLink): ?Link
    {
        $stmt = static::getPdo()->prepare(
            "select id, 
       (
           select urls.url from urls where urls.id = id
       ) as url,
       shortLink
       from links where shortLink=?"
        );
        $result = $stmt->execute([$shortLink]);
        $row = $stmt->fetch();
        if (empty($row)) {
            return null;
        }
        if ($result) {
            return new Link(
                id: $row['id'],
                url: $row['url'],
                shortUrl: $row['shortLink']
            );
        }
    }

}