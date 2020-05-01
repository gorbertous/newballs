#!/usr/bin/env bash

sudo ufw --force disable \
&& sudo ufw --force reset \
&& sudo ufw default deny incoming \
&& sudo ufw default allow outgoing \
&& sudo ufw allow 22/tcp \
&& sudo ufw allow 443/tcp \
&& sudo ufw --force enable