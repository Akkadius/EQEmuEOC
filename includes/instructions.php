<div id="wiki_page_c" class="wikidisplay">

  <div class="alert alert-danger" role="alert">
    <i class="fa fa-warning"></i>
    EOC is deprecated and no longer maintained. PEQ editor will continue to be maintained and updated.
  </div>

  <table
      border="1"
      cellpadding="1"
      cellspacing="1"
      style="line-height: 1.6em; width: 300px;"
      class="table table-striped table-bordered dataTable"
  >
    <tbody>
    <tr>
      <td style="width: 100%; text-align: center; vertical-align: middle;">
        <p style="line-height: 20.7999992370605px;">&nbsp;</p>

        <p style="line-height: 20.7999992370605px;"><img
              alt=""
              src="./instructions_files/eqemu.png"
              style="height: 76px; line-height: 20.7999992370605px; width: 158px;"
          ></p>

        <p style="line-height: 20.7999992370605px;"><img
              alt=""
              src="./instructions_files/eoc-fd.png"
              style="height: 48px; width: 200px;"
          ></p>
      </td>
    </tr>
    <tr>
      <td style="width: 100%; text-align: center; vertical-align: middle;">
        <span style="line-height: 20.7999992370605px;">This is the central place in which documentation is maintained for EOC 2.0 and its resources.</span>
      </td>
    </tr>
    </tbody>
  </table>

  <p>The purpose of EoC is to provide rapid server development tools that may or may not be available through other
    mediums.</p>

  <p>The goal is not to replace or duplicate effort but to make an extremely effective creative medium to develop and
    manage server's and their content respectively</p>

  <h3>EQEmu Operations Center (EoC) 2.0</h3>

  <ul>
    <li>Active status: development</li>
    <li>Sub tools: PEQ Editor managed and maintained by the PEQ team</li>
    <li><strong>How does it work?</strong>
      <ul>
        <li>Currently the EoC is a web tool that works as an external tool that does not require extensive web server
          setup, configs and many prerequisite knowledge to get development tools up and in place
        </li>
        <li>You simply need your MySQL (Database Server) to be opened up on port 3306 on your server. For some people
          this could mean:
          <ul>
            <li>Open up port 3306 on Windows Firewall</li>
            <li>Open up port 3306 on your home Router (Port Forwarding)</li>
          </ul>
        </li>
        <li>You also need to have a database user (NOT ROOT - IMPORTANT) custom created so that you add a database
          connection to connect to your server
        </li>
      </ul>
    </li>
  </ul>

  <table
      border="1"
      cellpadding="1"
      cellspacing="1"
      style="width: 800px;"
      class="table table-striped table-bordered dataTable"
  >
    <tbody>
    <tr>
      <td style="text-align: center;"><img
            alt=""
            src="./instructions_files/ZvxLMB4.png"
            style="width: 600px; height: 453px; border-width: 5px; border-style: solid;"
        ></td>
    </tr>
    <tr>
      <td style="text-align: center;">
        <p>High level reference of the communication architecture between your test/production server and the EOC.</p>

        <p>This is simply to explain that EOC needs to 'read' your database via port 3306 in order to have the EOC tools
          manipulate your database data.</p>
      </td>
    </tr>
    </tbody>
  </table>

  <h3>Create your Database User</h3>

  <ul>
    <li>To create your database user you will need to run the query below to create your database user:
      <ul>
        <li>This will allow connections only from EOC via the specified user below, you shouldn't need to change the
          password because you are only allowing from the EOC server
        </li>
        <li>USE YOUR OWN PASSWORD AND MAKE NOTE OF THE ONE BELOW, DO NOT LOSE IT!</li>
        <li>Subtitute "peq" for your database name if it is different</li>
      </ul>
    </li>
  </ul>

    <?php
    echo "<pre>
CREATE USER 'eoc_database_user'@'" . $SERVER_PUBLIC_IP . "' IDENTIFIED BY '" . generatePassword() . "';
GRANT GRANT OPTION ON peq.* TO 'eoc_database_user'@'" . $SERVER_PUBLIC_IP . "';
GRANT ALL ON peq.* TO 'eoc_database_user'@'" . $SERVER_PUBLIC_IP . "';
</pre>";

    ?>

  <h3>Accessing EoC</h3>

  <table
      border="1"
      cellpadding="1"
      cellspacing="1"
      style="line-height: 1.6em; width: 1200px;"
      class="table table-striped table-bordered dataTable"
  >
    <tbody>
    <tr>
      <td style="width: 400px;">
        <ul>
          <li>Browse to:&nbsp;<a
                href="http://eoc.akkadius.com/EOC2/login.php"
                class="reglink tooltips"
                data-placement="top"
                style="background: url(&quot;includes/img?GetFavicon=http%3A%2F%2Feoc.akkadius.com%2FEOC2%2Flogin.php&amp;type=http&quot;) left center no-repeat; padding-left: 18px;"
                data-original-title=""
                title=""
            >http://eoc.akkadius.com/EOC2/login.php</a>

            <ul style="margin-bottom: 0px;">
              <li>(You may want to bookmark it)</li>
            </ul>
          </li>
          <li>You will need to create your connection or connections, in EOC 2.0 you can create multiple connections and
            it will be stored in your browser
          </li>
          <li>In the example you need to input&nbsp;
            <ul>
              <li>Hostname or IP</li>
              <li>Database Name</li>
              <li>Database User</li>
              <li>Database Password</li>
            </ul>
          </li>
          <li>Once you have verified a successful connection, you are able to login</li>
        </ul>
      </td>
      <td style="text-align: center;"><img
            alt=""
            src="./instructions_files/eoc_db_connect.PNG"
            style="height: 664px; border-width: 1px; border-style: solid; line-height: 20.7999992370605px; width: 348px;"
        >
      </td>
    </tr>
    <tr>
      <td style="width: 400px;">
        <ul>
          <li>Once you have succesfully logged in, you have created a connection which is stored in a cookie on your
            browser and you can quickly flip between your stored databases
          </li>
          <li>When you are in the EOC interface you will be able to see your stored connections at the top right and be
            able to swap to and from them almost instantly
          </li>
        </ul>
      </td>
      <td style="text-align: center;"><img
            alt=""
            src="./instructions_files/eoc_connections.png"
            style="border-width: 1px; border-style: solid; width: 221px; height: 253px;"
        ></td>
    </tr>
    </tbody>
  </table>

  <h3>Tools</h3>

  <table
      border="1"
      cellpadding="1"
      cellspacing="1"
      style="width: 100%;"
      class="table table-striped table-bordered dataTable"
  >
    <tbody>
    <tr>
      <td style="text-align: center; width: 200px; background-color: rgb(204, 255, 255);"><strong>Tool</strong></td>
      <td style="text-align: center; width: 350px; background-color: rgb(204, 255, 255);"><strong>Features</strong></td>
      <td style="text-align: center; width: 100px; background-color: rgb(204, 255, 255);"><strong>Development
          Status</strong></td>
      <td style="text-align: center; width: 150px; background-color: rgb(204, 255, 255);"><strong>Developed and/or
          Maintained By</strong></td>
      <td style="text-align: center; width: 850px; background-color: rgb(204, 255, 255);">
        <strong style="line-height: 20px;">How to Use Guides and Extra Information</strong></td>
    </tr>
    <tr>
      <td style="text-align: center;">
        <h3>PEQ Editor</h3>

        <p><img alt="" src="./instructions_files/PEQ_Logo.png" style="width: 150px; height: 49px;"><img
              alt=""
              src="./instructions_files/peqe_logo.png"
              style="height: 58px; line-height: 20px; text-align: center; width: 75px;"
          ></p>

        <p>&nbsp;</p>

        <p>The PEQ Editor is an entire editor suite on its own accessible from EoC. This is so users do not have to
          setup their own for convenience and/or lack of knowledge.</p>

        <p>This may have features or capabilities that aren't available otherwise in other similar editors in the
          EOC.</p>
      </td>
      <td style="text-align: center;">
        <div style="text-align: left;"><span style="background-color: transparent;"><strong>Support Forums:</strong> <a
                href="http://www.peqtgc.com/phpBB3/"
                class="reglink tooltips"
                data-placement="top"
                style="background: url(&quot;includes/img?GetFavicon=http%3A%2F%2Fwww.peqtgc.com%2FphpBB3%2F&amp;type=http&quot;) left center no-repeat; padding-left: 18px;"
                data-original-title=""
                title=""
            >PEQ Forums</a></span></div>

        <div style="text-align: left;">
          The<span style="white-space:pre"><span style="white-space: normal;"> </span></span>PEQ Editor provides
          facilities to edit the following things in the database:
        </div>

        <ul>
          <li style="text-align: left;">NPCs</li>
          <li style="text-align: left;">Spawns</li>
          <li style="text-align: left;">Loot</li>
          <li style="text-align: left;">Spell Sets</li>
          <li style="text-align: left;">Merchants</li>
          <li style="text-align: left;">Faction Hits</li>
          <li style="text-align: left;">Faction Defaults</li>
          <li style="text-align: left;">Tradeskills</li>
          <li style="text-align: left;">Adventures</li>
          <li style="text-align: left;">Tasks</li>
          <li style="text-align: left;">Objects</li>
          <li style="text-align: left;">Zone Data</li>
          <li style="text-align: left;">Server Data</li>
          <li style="text-align: left;">More</li>
        </ul>

        <div style="text-align: left;">&nbsp;</div>
      </td>
      <td style="text-align: center;"><strong>Active</strong></td>
      <td style="text-align: center;">
        <p><strong>The PEQ Team</strong></p>

        <p><span style="line-height: 20px;">This editor has been developed by various PEQ team members over time, and it is constantly evolving as PEQ uses it to further build their database.</span>
        </p>
      </td>
      <td style="text-align: center;"><img
            alt=""
            src="./instructions_files/eoc_peq_loc.png"
            style="width: 547px; height: 336px; border-width: 1px; border-style: solid;"
        ></td>
    </tr>
    <tr>
      <td style="text-align: center;">
        <h4>Zone Copy/Import</h4>
      </td>
      <td style="vertical-align: top;">
        <ul>
          <li>An extremely powerful tool made to be able to copy entire zones and create 'partitions' if you will.</li>
          <li><strong>EQEmu leverages instancing</strong> and you can have two completely different versions of a zone,
            this tool allows you to not start from scratch and at least make a copy of the base data in the zone before
            modifying all of the existing spawns.
          </li>
          <li>This copies:
            <ul>
              <li>Doors</li>
              <li>Objects</li>
              <li>NPC's</li>
              <li>Spawns</li>
              <li>Grids</li>
            </ul>
          </li>
          <li>Copy Types:
            <ul>
              <li>Full - Will copy everything</li>
              <li>Partial - Will just copy over spawn data, not create new NPC's</li>
            </ul>
          </li>
          <li><strong>You can import content from a second database </strong>connection. So out of a list of your
            connections you can pick a zone from a different database and it will import all the NPC's
            <ul>
              <li>You cannot&nbsp;</li>
              <li>The downside of this is that all of the NPC's have to be actually spawned in the zone and the ID's
                change, so if you are importing content you have to be mindful that ID's need to change in your scripts
                and whatever else that may tie to them
              </li>
            </ul>
          </li>
        </ul>

        <p>&nbsp;</p>
      </td>
      <td style="text-align: center;"><strong>Active/Complete</strong></td>
      <td style="text-align: left;">
        <ul>
          <li>Akkadius</li>
        </ul>
      </td>
      <td style="text-align: center;"><img
            alt=""
            src="./instructions_files/eoc_zone_copy_import.PNG"
            style="width: 650px; height: 533px; border-width: 1px; border-style: solid;"
        ></td>
    </tr>
    <tr>
      <td style="text-align: center;">
        <h4>Item Editor</h4>
      </td>
      <td>
        <p>Making an item editor is flat out an absolute pain in the ass. There's an insane amount of data to translate,
          this one is very functional but still has some of its quirks.</p>

        <ul>
          <li>Full and extensive Item Searching and filters</li>
          <li>Quick Item Stat Scaling (Auto Scaler)</li>
          <li>Visual Item Color Selector</li>
          <li>Visual Class Selector</li>
          <li>Visual Icon and Weapon Viewing/Selecting</li>
          <li>Field Descriptions</li>
          <li>Quick Free Item ID Slot picker</li>
          <li>Visual Class picker</li>
          <li>Visual Race Picker</li>
          <li>Visual Inventory slot picker</li>
          <li>Spell data visual search</li>
          <li>Extensive data field translators</li>
          <li>Does not break to database schema changes</li>
        </ul>
      </td>
      <td style="text-align: center;">
        <p><strong>Active/Functional</strong></p>

        <p>This tool could use some sharpening but is very functional.</p>
      </td>
      <td style="text-align: left;">
        <ul>
          <li>Akkadius</li>
        </ul>
      </td>
      <td style="text-align: center;"><img
            alt=""
            src="./instructions_files/eoc_item_search.png"
            style="border-width: 1px; border-style: solid; width: 800px; height: 419px;"
        ></td>
    </tr>
    <tr>
      <td style="text-align: center;">
        <h4>Task Editor</h4>
      </td>
      <td>
        <p>Here is a very fine and functional task editor originally created by Trevius. I went through and modified
          many things for the port to EOC 2.0 from EOC 1.0 but all of the functionality very much remains the same. This
          has been my goto utility for editing tasks and for many others.</p>

        <ul>
          <li>No page loads, all AJAX seamless</li>
          <li>Editing existing Tasks</li>
          <li>Creating New Tasks</li>
          <li>Creating Task Activities</li>
          <li>Easy to use task paramters</li>
          <li>Realtime saving</li>
          <li>Easy to use interface</li>
        </ul>
      </td>
      <td style="text-align: center;">
        <p><strong style="line-height: 20px; text-align: center;">Active/Complete</strong></p>

        <p>Believe there are some small quirks from the port left over</p>
      </td>
      <td style="text-align: left;">
        <ul>
          <li>Trevius</li>
          <li>Akkadius</li>
        </ul>
      </td>
      <td style="text-align: center;"><img
            alt=""
            src="./instructions_files/eoc_task_prev.PNG"
            style="border-width: 1px; border-style: solid; width: 800px; height: 471px;"
        ></td>
    </tr>
    <tr>
      <td style="text-align: center;">
        <h4>NPC Editor</h4>
      </td>
      <td>
        <ul>
          <li>Grid like NPC Editor that allows you to search a zone by its instance ID as well as NPC Name as a filter
          </li>
          <li>Allows for Mass Field Editing, the powerful aspect of this editor as it allows you to make mass changes to
            a zone of NPC's based on certain critera.
          </li>
          <li>This tools goes GREAT with the zone copier tool for instancing, you can copy a zone into a new version and
            then massively modify the new data to make the zone appear completely different and do alot of work very
            quickly
          </li>
          <li>Individual NPC Editing allows you to get a visual and translated reference of editing individual npc_types
            field data
          </li>
        </ul>
      </td>
      <td style="text-align: center;">
        <p><strong>Active/Functional</strong></p>

        <p><strong>Known issues:</strong></p>

        <p>Sluggish when doing individual NPC editing when you have many results loaded in a zone. Fixing this would
          require a rewrite of the grid loading. What you see currently is a port from EOC 1.0</p>
      </td>
      <td style="text-align: left;">
        <ul>
          <li>Akkadius</li>
        </ul>
      </td>
      <td style="text-align: center;"><img
            alt=""
            src="./instructions_files/eoc_npc_editor.PNG"
            style="border-width: 1px; border-style: solid; width: 800px; height: 513px;"
        ></td>
    </tr>
    <tr>
      <td style="text-align: center;">
        <h4>dbstr_us.txt editor</h4>

        <p>Client File</p>
      </td>
      <td>
        <ul>
          <li>This tool was originally meant to be a place you can upload your EverQuest clients working dbstr_us.txt
            and reference it in other EOC tools and utilites as well such as the Spell Editor that is currently in
            development
          </li>
          <li>You can upload a local dbstr_us.txt and the server remembers you by your browser so be careful if you plan
            to use this for extended use you should download a copy of the text file when you are done working with it
          </li>
          <li>You can edit and search for entries quickly with your uploaded and working version</li>
        </ul>
      </td>
      <td style="text-align: center;"><strong>Active/In-Development</strong></td>
      <td style="text-align: left;">
        <ul>
          <li>Akkadius</li>
        </ul>
      </td>
      <td style="text-align: center;"><img
            alt=""
            src="./instructions_files/eoc_dbstr_editor.png"
            style="border-width: 1px; border-style: solid; width: 800px; height: 453px;"
        ></td>
    </tr>
    <tr>
      <td style="text-align: center;">
        <h4>_chr.txt File Editor</h4>

        <p>Client File</p>
      </td>
      <td>
        <ul>
          <li>This tool takes some really hard to find information and makes a very easy visual tool to create custom
            race model loaders for zones
          </li>
          <li>When the client loads zone graphic assets, it looks for this character text file to know what models to
            load. If I zoned into crushbone it would be crushbone_chr.txt, this format uses the zone short name in the
            client folder.
          </li>
          <li>Be wary when using this that sometimes loading particular models can crash the client, so make sure you
            test your files before giving them to clients
            <ul>
              <li>You can search for race models and visually select which ones you want included in your custom
                loader
              </li>
            </ul>
          </li>
        </ul>
      </td>
      <td style="text-align: center;"><strong>Active/Complete</strong></td>
      <td style="text-align: left;">
        <ul style="line-height: 20px;">
          <li>Akkadius</li>
        </ul>
      </td>
      <td style="text-align: center;"><img
            alt=""
            src="./instructions_files/eoc_character_file_gen.PNG"
            style="border-width: 1px; border-style: solid; width: 900px; height: 408px;"
        ></td>
    </tr>
    <tr>
      <td style="text-align: center;">
        <h4>Race Viewer</h4>
      </td>
      <td>
        <ul>
          <li>Simple to use visual Race Viewer
            <ul>
              <li>Provides search by ID or Name</li>
            </ul>
          </li>
        </ul>
      </td>
      <td style="text-align: center;"><strong style="line-height: 20px; text-align: center;">Active/Complete</strong>
      </td>
      <td style="text-align: left;">
        <ul style="line-height: 20px;">
          <li>Akkadius</li>
        </ul>
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td style="text-align: center;">
        <h4>Spell Icon Viewer</h4>
      </td>
      <td>
        <ul>
          <li>Simple to use spell icon viewer</li>
          <li>Also used in the Spell Editor</li>
        </ul>
      </td>
      <td style="text-align: center;"><strong style="line-height: 20px; text-align: center;">Active/Complete</strong>
      </td>
      <td style="text-align: left;">
        <ul style="line-height: 20px;">
          <li>Akkadius</li>
        </ul>
      </td>
      <td style="text-align: center;"><img
            alt=""
            src="./instructions_files/eoc_spell_icon_view.PNG"
            style="width: 700px; height: 389px; border-width: 1px; border-style: solid;"
        ></td>
    </tr>
    <tr>
      <td style="text-align: center;">
        <h4>Item Icon Viewer</h4>
      </td>
      <td>
        <ul>
          <li>Simple to use item icon viewer and searcher, also used in the Item Editor</li>
        </ul>
      </td>
      <td style="text-align: center;"><strong style="line-height: 20px; text-align: center;">Active/Complete</strong>
      </td>
      <td style="text-align: left;">
        <ul style="line-height: 20px;">
          <li>Akkadius</li>
        </ul>
      </td>
      <td style="text-align: center;"><img
            alt=""
            src="./instructions_files/eoc_item_icon_view.PNG"
            style="width: 700px; height: 523px; border-width: 1px; border-style: solid;"
        ></td>
    </tr>
    </tbody>
  </table>

  <p>&nbsp;</p>

</div>
