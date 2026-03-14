You need to enable MPD's software mixer. Edit the MPD config:
bashsudo nano /etc/mpd.conf
```

Find your `audio_output` block and change `mixer_type` to `software`, or add it if missing:
```
audio_output {
    type            "alsa"
    name            "My ALSA Device"
    mixer_type      "software"
}
Then restart MPD:
bash
sudo systemctl restart mpd
