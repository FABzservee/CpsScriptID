<!DOCTYPE html>
<html>
<head>
    <title>Update and Encrypt Lua Script</title>
</head>
<body>
    <h2>Update and Encrypt Lua Script User ID</h2>
    <form method="post" action="">
        <label for="userID">Valid User ID:</label><br>
        <input type="text" id="userID" name="userID" value="699606"><br><br>
        <input type="submit" name="submit" value="Update and Encrypt Script">
    </form>

    <?php
    if (isset($_POST['submit'])) {
        // Ambil ID dari form
        $newUserID = $_POST['userID'];

        // Template script Lua
        $luaScript = '
        Gems = 0 -- Collect Gems 1= On 0=Off
        consume = true
        background = 3004 
        POSBFGX = 39
        POSBFGY = 194

        -- ID pemain yang valid
        local validUserID = ' . $newUserID . ' -- Updated ID

        -- Fungsi untuk memeriksa ID pemain
        function checkUserID()
            local playerID = GetLocal().userID
            if playerID == validUserID then
                -- (Skrip dilanjutkan seperti contoh sebelumnya)
                IkanCommunity()
            else
                LogToConsole("`0[ `4Buy Script In `bMy Discord `0]")
                SendPacket(2, "action|input\n|text|`0[`b PNB`0 ] `4Wrong User ID") 
                Sleep(4000) 
                SendPacket(2, "action|input\n|text|`0[`b PNB`0 ] `cBuy Script In `b: `2/BuySC") 
            end
        end

        -- Panggil fungsi untuk memeriksa ID
        checkUserID()';

        // Mengenkripsi script menggunakan base64_encode
        $encryptedScript = base64_encode($luaScript);

        // Format hasil enkripsi untuk Lua Executor
        $luaExecutorCode = "
        local encrypted_script = '" . $encryptedScript . "'

        function decrypt(base64)
            local b = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'
            base64 = string.gsub(base64, '[^'..b..'=]', '')
            return (base64:gsub('.', function(x)
                if (x == '=') then return '' end
                local r,f='', (b:find(x)-1)
                for i=6,1,-1 do r=r..(f%2^i-f%2^(i-1) > 0 and '1' or '0') end
                return r;
            end):gsub('%d%d%d?%d?%d?%d?%d?%d?', function(x)
                if (#x ~= 8) then return '' end
                local c=0
                for i=1,8 do c=c+(x:sub(i,i)=='1' and 2^(8-i) or 0) end
                return string.char(c)
            end))
        end

        local decrypted = decrypt(encrypted_script)
        assert(loadstring(decrypted))()
        ";

        // Simpan script ke file
        file_put_contents('encrypted_script.lua', $luaExecutorCode);
        echo "<p>Lua script has been encrypted and saved as encrypted_script.lua with User ID: $newUserID</p>";
    }
    ?>
</body>
</html>
