<div class="flex flex-col gap-4 mt-10">
    <?php foreach ($_SESSION["content"] as $video): ?>
        <div class="w-full h-[150px] flex flex-row bg-gray-700">
            <div class="w-[30%] h-full bg-red-400">
                <img src=<?= $video["thumbnail"] ?> alt="thumbnail.jpeg" class="w-full h-full">
            </div>
            <div class="w-[70%] h-full p-3 flex flex-col justify-between">
                <div class="flex flex-col items-start">
                    <p class="text-bold text-xl text-white"><?= $video["title"] ?></p>
                    <p class="text-white text-sm opacity-[50%]">
                        <?php
                        $hour = $video["length"] / 3600;
                        $min = ($video["length"] % 3600) / 60;
                        $sec = $video["length"] % 60;
                        echo intval($hour) . ":" . intval($min) . ":" . intval($sec);
                        ?>
                    </p>
                </div>
                <div class="flex">
                    <a class="bg-blue-400 hover:bg-blue-500 text-white font-medium rounded-lg text-sm px-4 py-2"
                        href=<?= $video["download_url"] ?>>Download</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>