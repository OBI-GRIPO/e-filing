#!/bin/bash
cd /var/www/html

		# see http://stackoverflow.com/a/2705678/433558
		sed_escape_lhs() {
			echo "$@" | sed -e 's/[]\/$*.^|[]/\\&/g'
		}
		sed_escape_rhs() {
			echo "$@" | sed -e 's/[\/&]/\\&/g'
		}
		php_escape() {
			local escaped="$(php -r 'var_export(('"$2"') $argv[1]);' -- "$1")"
			if [ "$2" = 'string' ] && [ "${escaped:0:1}" = "'" ]; then
				escaped="${escaped//$'\n'/"' + \"\\n\" + '"}"
			fi
			echo "$escaped"
		}
		set_config() {
			key="$1"
			value="$2"
			var_type="${3:-string}"
			if grep -Fxq "$key" wp-config.php
              then
			   start="(['\"])$(sed_escape_lhs "$key")\2\s*,"
			   end="\);"
		    	if [ "${key:0:1}" = '$' ]; then
				   start="^(\s*)$(sed_escape_lhs "$key")\s*="
			    	end=";"
			    fi
			   sed -ri -e "s/($start\s*).*($end)$/\1$(sed_escape_rhs "$(php_escape "$value" "$var_type")")\3/" wp-config.php
            else
               start="^\s\*\/$"
               sed -i "/$start/a define($(sed_escape_rhs "$(php_escape "$key" "$var_type")"),$(sed_escape_rhs "$(php_escape "$value" "$var_type")"));" wp-config.php
            fi
					}

set_config 'FS_METHOD' "direct"

